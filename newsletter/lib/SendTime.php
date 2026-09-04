<?php
/**
 * SendTime – lernt aus den Öffnungen, wann die Empfänger am ehesten lesen,
 * und leitet daraus eine optimale Versandzeit ab (Wochentag + Uhrzeit).
 *
 * Grundlage sind die „open"-Ereignisse mit ihrem Zeitstempel (events.created_at,
 * Ortszeit „Y-m-d H:i:s"). Jedes Segment (Liste) lässt sich getrennt auswerten –
 * so bekommt jedes Segment seine eigene beste Zeit. Bewusst ohne Datenbank-
 * Datumsfunktionen: der Wochentag wird in PHP berechnet, damit dasselbe unter
 * SQLite wie unter MySQL/MariaDB läuft.
 */
final class SendTime
{
    /** Auswertungsfenster in Tagen (rückwärts ab heute). */
    private const FENSTER_TAGE = 365;

    /** So viele Öffnungen müssen mindestens vorliegen, sonst keine Empfehlung. */
    private const MIN_OEFFNUNGEN = 25;

    /**
     * Beste Versandzeit für ein Segment (Liste) oder – bei null – für alle
     * aktiven Empfänger. null, wenn noch zu wenig Öffnungen gesammelt wurden.
     *
     * @return array{weekday:int,hour:int,minute:int,opens:int,total:int,share:float,confidence:string}|null
     */
    public static function optimal(?int $listId = null): ?array
    {
        return self::auswerten(self::sammeln($listId));
    }

    /**
     * Rohdaten für die Wochen-Heatmap: je Wochentag (1–7) und Stunde (0–23)
     * die Zahl der Öffnungen, dazu der Höchstwert und die Gesamtzahl.
     *
     * @return array{grid:array<int,array<int,int>>,max:int,total:int}
     */
    public static function heatmap(?int $listId = null): array
    {
        $daten = self::sammeln($listId);
        $max = 0;
        foreach ($daten['wh'] as $stunden) {
            foreach ($stunden as $n) {
                $max = max($max, $n);
            }
        }
        return ['grid' => $daten['wh'], 'max' => $max, 'total' => $daten['total']];
    }

    /**
     * Beste Zeit je Segment – für die Übersicht. Enthält zuerst „alle aktiven
     * Empfänger", dann jede Liste. Jede Zeile trägt die Empfehlung (oder null).
     *
     * @return array<int,array{id:?int,name:string,active:int,optimal:?array}>
     */
    public static function perList(): array
    {
        $counts = Lists::activeCounts();
        $gesamtAktiv = (int) DB::value("SELECT COUNT(*) FROM subscribers WHERE status = 'active'", [], 0);

        $zeilen = [[
            'id'      => null,
            'name'    => 'Alle aktiven Empfänger',
            'active'  => $gesamtAktiv,
            'optimal' => self::optimal(null),
        ]];
        foreach (Lists::all() as $list) {
            $id = (int) $list['id'];
            $zeilen[] = [
                'id'      => $id,
                'name'    => (string) $list['name'],
                'active'  => $counts[$id] ?? 0,
                'optimal' => self::optimal($id),
            ];
        }
        return $zeilen;
    }

    /** Nächster zukünftiger Zeitpunkt mit diesem Wochentag/Uhrzeit als „Y-m-d H:i:s". */
    public static function nextOccurrence(int $weekday, int $hour, int $minute): string
    {
        $now = time();
        for ($i = 0; $i <= 14; $i++) {
            $tag = strtotime("+$i day", $now);
            if ((int) date('N', $tag) !== $weekday) {
                continue;
            }
            $kandidat = mktime($hour, $minute, 0, (int) date('n', $tag), (int) date('j', $tag), (int) date('Y', $tag));
            if ($kandidat !== false && $kandidat > $now + 120) {
                return date('Y-m-d H:i:s', $kandidat);
            }
        }
        return date('Y-m-d H:i:s', $now + 3600);
    }

    /** „Dienstag, 18:30 Uhr" */
    public static function label(array $optimal): string
    {
        return self::weekdayName((int) $optimal['weekday'])
            . ', ' . sprintf('%02d:%02d', (int) $optimal['hour'], (int) $optimal['minute']) . ' Uhr';
    }

    public static function weekdayName(int $n): string
    {
        $namen = [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag',
                  5 => 'Freitag', 6 => 'Samstag', 7 => 'Sonntag'];
        return $namen[$n] ?? '—';
    }

    public static function weekdayShort(int $n): string
    {
        $namen = [1 => 'Mo', 2 => 'Di', 3 => 'Mi', 4 => 'Do', 5 => 'Fr', 6 => 'Sa', 7 => 'So'];
        return $namen[$n] ?? '?';
    }

    /* ------------------------------------------------------------------ intern */

    /**
     * Öffnungen einsammeln und zu Wochentag×Stunde bündeln.
     *
     * @return array{wh:array<int,array<int,int>>,whm:array<int,array<int,array<int,int>>>,total:int}
     */
    private static function sammeln(?int $listId): array
    {
        $seit = date('Y-m-d H:i:s', time() - self::FENSTER_TAGE * 86400);

        // Bündelung schon in der Datenbank auf „YYYY-MM-DD HH:M" (Zehner-Minute) –
        // das hält die Zeilenzahl klein und bleibt portabel (nur substr, kein DATE()).
        if ($listId !== null && $listId > 0) {
            $rows = DB::all(
                "SELECT substr(e.created_at, 1, 15) AS bucket, COUNT(*) AS n
                 FROM events e
                 JOIN subscriber_lists sl ON sl.subscriber_id = e.subscriber_id
                 WHERE e.type = 'open' AND e.created_at >= ? AND sl.list_id = ?
                 GROUP BY substr(e.created_at, 1, 15)",
                [$seit, $listId]
            );
        } else {
            $rows = DB::all(
                "SELECT substr(created_at, 1, 15) AS bucket, COUNT(*) AS n
                 FROM events
                 WHERE type = 'open' AND created_at >= ?
                 GROUP BY substr(created_at, 1, 15)",
                [$seit]
            );
        }

        $wh = [];   // Wochentag => Stunde => Anzahl
        $whm = [];  // Wochentag => Stunde => Zehner-Minute (0–5) => Anzahl
        $total = 0;
        foreach ($rows as $row) {
            $bucket = (string) $row['bucket'];
            if (strlen($bucket) < 15) {
                continue;
            }
            $n = (int) $row['n'];
            $datum = substr($bucket, 0, 10);
            $ts = strtotime($datum);
            if ($ts === false) {
                continue;
            }
            $wd   = (int) date('N', $ts);          // 1 = Montag … 7 = Sonntag
            $hour = (int) substr($bucket, 11, 2);
            $mten = (int) substr($bucket, 14, 1);  // Zehnerstelle der Minute (0–5)
            if ($hour < 0 || $hour > 23) {
                continue;
            }
            $wh[$wd][$hour]        = ($wh[$wd][$hour] ?? 0) + $n;
            $whm[$wd][$hour][$mten] = ($whm[$wd][$hour][$mten] ?? 0) + $n;
            $total += $n;
        }
        return ['wh' => $wh, 'whm' => $whm, 'total' => $total];
    }

    /**
     * Aus den gebündelten Daten das beste Fenster bestimmen.
     *
     * @param array{wh:array,whm:array,total:int} $daten
     */
    private static function auswerten(array $daten): ?array
    {
        $total = (int) $daten['total'];
        if ($total < self::MIN_OEFFNUNGEN) {
            return null;
        }

        // Bestes Wochentag/Stunde-Fenster suchen.
        $bestWd = 0; $bestHour = 0; $bestN = -1;
        foreach ($daten['wh'] as $wd => $stunden) {
            foreach ($stunden as $hour => $n) {
                if ($n > $bestN) {
                    $bestN = $n; $bestWd = (int) $wd; $bestHour = (int) $hour;
                }
            }
        }
        if ($bestN <= 0) {
            return null;
        }

        // Repräsentative Minute: häufigste Zehner-Minute im Siegerfenster.
        $minute = 0; $bestM = -1;
        foreach (($daten['whm'][$bestWd][$bestHour] ?? []) as $mten => $n) {
            if ($n > $bestM) { $bestM = $n; $minute = ((int) $mten) * 10; }
        }

        $share = $total > 0 ? $bestN / $total : 0.0;
        $confidence = $total >= 120 ? 'hoch' : ($total >= 50 ? 'mittel' : 'niedrig');

        return [
            'weekday'    => $bestWd,
            'hour'       => $bestHour,
            'minute'     => $minute,
            'opens'      => $bestN,
            'total'      => $total,
            'share'      => $share,
            'confidence' => $confidence,
        ];
    }
}
