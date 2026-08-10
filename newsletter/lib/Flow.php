<?php
/**
 * Flow – der Ablauf einer Automation.
 *
 * Ein Ablauf ist eine Kette von Knoten, die im Baukasten per Drag & Drop
 * zusammengezogen wird:
 *
 *   [Anmeldung bestätigt]
 *        ↓
 *   [24 Stunden warten]
 *        ↓
 *   [E-Mail: Willkommen]
 *        ↓
 *   [Wenn geöffnet?] ── ja ─→ [E-Mail: Praxisbeispiel]
 *        └────────── nein ─→ [3 Tage warten] → [E-Mail: Erinnerung]
 *
 * Diese Klasse kennt nur die Struktur: prüfen, absichern und die Frage
 * beantworten „welcher Knoten kommt als Nächstes?". Das Ausführen erledigt
 * Automations::tick(), das Versenden wie gehabt die Warteschlange.
 */
final class Flow
{
    /** Knotenarten und ihre Bezeichnung in der Oberfläche. */
    public const TYPES = [
        'warten'    => 'Warten',
        'mail'      => 'E-Mail senden',
        'bedingung' => 'Wenn … dann',
        'aktion'    => 'Aktion',
        'ende'      => 'Strecke beenden',
    ];

    /** Womit eine Bedingung prüft. */
    public const CONDITIONS = [
        'geoeffnet'  => 'hat die letzte Mail dieser Strecke geöffnet',
        'geklickt'   => 'hat in der letzten Mail dieser Strecke geklickt',
        'in_liste'   => 'steht in einer bestimmten Liste',
        'hat_firma'  => 'hat ein Unternehmen hinterlegt',
    ];

    /** Was eine Aktion tun kann. */
    public const ACTIONS = [
        'liste_hinzufuegen' => 'zu einer Liste hinzufügen',
        'liste_entfernen'   => 'aus einer Liste entfernen',
        'abmelden'          => 'vom Newsletter abmelden',
    ];

    public const UNITS = [
        'minuten' => 'Minuten',
        'stunden' => 'Stunden',
        'tage'    => 'Tage',
    ];

    /** Dieselben Einheiten in der Einzahl – „1 Tag warten“ statt „1 Tage warten“. */
    public const UNITS_ONE = [
        'minuten' => 'Minute',
        'stunden' => 'Stunde',
        'tage'    => 'Tag',
    ];

    /** Beschriftung einer Wartezeit, in Ein- oder Mehrzahl. */
    public static function waitLabel(int $value, string $einheit): string
    {
        $labels = $value === 1 ? self::UNITS_ONE : self::UNITS;
        return $value . ' ' . ($labels[$einheit] ?? $labels['tage']) . ' warten';
    }

    /* ------------------------------------------------------------- Anlegen */

    /** Ein neuer Knoten mit sinnvollen Vorgaben. */
    public static function node(string $type, array $data = []): array
    {
        $defaults = [
            'warten'    => ['value' => 1, 'einheit' => 'tage'],
            'mail'      => ['step_id' => 0],
            'bedingung' => ['pruefung' => 'geoeffnet', 'list_id' => 0, 'ja' => [], 'nein' => []],
            'aktion'    => ['aktion' => 'liste_hinzufuegen', 'list_id' => 0],
            'ende'      => [],
        ];
        return ['id' => 'n' . bin2hex(random_bytes(4)), 'type' => $type]
            + $data + ($defaults[$type] ?? []);
    }

    /** Vorschlag für eine neue Strecke: warten → Mail. */
    public static function starter(): array
    {
        return ['nodes' => [
            self::node('warten', ['value' => 1, 'einheit' => 'stunden']),
            self::node('mail'),
        ]];
    }

    /* -------------------------------------------------------------- Prüfen */

    /**
     * Liest den Ablauf aus dem Editor ein und säubert ihn.
     * @return array{nodes:array<int,array<string,mixed>>}
     */
    public static function parse(string $json): array
    {
        $data = json_decode($json, true);
        $nodes = is_array($data) ? ($data['nodes'] ?? $data) : [];
        return ['nodes' => self::cleanList(is_array($nodes) ? $nodes : [], 0)];
    }

    /** @return array<int,array<string,mixed>> */
    private static function cleanList(array $list, int $tiefe): array
    {
        $out = [];
        foreach ($list as $raw) {
            if (!is_array($raw) || count($out) >= 40) {
                continue;
            }
            $node = self::cleanNode($raw, $tiefe);
            if ($node !== null) {
                $out[] = $node;
            }
        }
        return $out;
    }

    /** @return array<string,mixed>|null */
    private static function cleanNode(array $raw, int $tiefe): ?array
    {
        $type = (string) ($raw['type'] ?? '');
        if (!isset(self::TYPES[$type])) {
            return null;
        }
        $id   = preg_replace('/[^a-z0-9]/i', '', (string) ($raw['id'] ?? '')) ?: ('n' . bin2hex(random_bytes(4)));
        $node = ['id' => mb_substr($id, 0, 24), 'type' => $type];

        switch ($type) {
            case 'warten':
                $node['einheit'] = isset(self::UNITS[$raw['einheit'] ?? '']) ? (string) $raw['einheit'] : 'tage';
                $node['value']   = max(1, min(365, (int) ($raw['value'] ?? 1)));
                break;

            case 'mail':
                $node['step_id'] = max(0, (int) ($raw['step_id'] ?? 0));
                break;

            case 'bedingung':
                $node['pruefung'] = isset(self::CONDITIONS[$raw['pruefung'] ?? ''])
                    ? (string) $raw['pruefung'] : 'geoeffnet';
                $node['list_id']  = max(0, (int) ($raw['list_id'] ?? 0));
                // Verschachtelung begrenzen, damit niemand sich verläuft
                $node['ja']   = $tiefe < 2 ? self::cleanList((array) ($raw['ja'] ?? []), $tiefe + 1) : [];
                $node['nein'] = $tiefe < 2 ? self::cleanList((array) ($raw['nein'] ?? []), $tiefe + 1) : [];
                break;

            case 'aktion':
                $node['aktion']  = isset(self::ACTIONS[$raw['aktion'] ?? ''])
                    ? (string) $raw['aktion'] : 'liste_hinzufuegen';
                $node['list_id'] = max(0, (int) ($raw['list_id'] ?? 0));
                break;
        }
        return $node;
    }

    /* ------------------------------------------------------ Wegbeschreibung */

    /**
     * Baut eine Übersicht: welcher Knoten folgt auf welchen.
     *
     * @return array{nodes:array<string,array>,next:array<string,?string>,
     *               ja:array<string,?string>,nein:array<string,?string>,first:?string}
     */
    public static function index(array $flow): array
    {
        $index = ['nodes' => [], 'next' => [], 'ja' => [], 'nein' => [], 'first' => null];

        $walk = static function (array $list, ?string $danach) use (&$walk, &$index): void {
            foreach ($list as $i => $node) {
                $id     = (string) $node['id'];
                $folgt  = isset($list[$i + 1]) ? (string) $list[$i + 1]['id'] : $danach;
                $index['nodes'][$id] = $node;
                $index['next'][$id]  = $folgt;

                if ($node['type'] === 'bedingung') {
                    $ja   = $node['ja']   ?? [];
                    $nein = $node['nein'] ?? [];
                    $index['ja'][$id]   = $ja   !== [] ? (string) $ja[0]['id']   : $folgt;
                    $index['nein'][$id] = $nein !== [] ? (string) $nein[0]['id'] : $folgt;
                    $walk($ja, $folgt);
                    $walk($nein, $folgt);
                }
            }
        };

        $nodes = $flow['nodes'] ?? [];
        $walk($nodes, null);
        $index['first'] = $nodes !== [] ? (string) $nodes[0]['id'] : null;
        return $index;
    }

    /** Alle Mail-Knoten (auch in Zweigen) – für Statistik und Aufräumen. */
    public static function mailNodes(array $flow): array
    {
        $out = [];
        foreach (self::index($flow)['nodes'] as $node) {
            if ($node['type'] === 'mail' && (int) $node['step_id'] > 0) {
                $out[] = $node;
            }
        }
        return $out;
    }

    /** Wartezeit eines Knotens in Sekunden. */
    public static function seconds(array $node): int
    {
        $wert = max(1, (int) ($node['value'] ?? 1));
        return match ((string) ($node['einheit'] ?? 'tage')) {
            'minuten' => $wert * 60,
            'stunden' => $wert * 3600,
            default   => $wert * 86400,
        };
    }

    /** Beschreibung eines Knotens im Klartext (Oberfläche und Protokoll). */
    public static function describe(array $node): string
    {
        switch ((string) $node['type']) {
            case 'warten':
                return self::waitLabel((int) $node['value'], (string) $node['einheit']);
            case 'mail':
                $step = (int) $node['step_id'] > 0 ? Automations::step((int) $node['step_id']) : null;
                $betreff = trim((string) ($step['subject'] ?? ''));
                return 'E-Mail: ' . ($betreff !== '' ? $betreff : 'noch ohne Betreff');
            case 'bedingung':
                $text = self::CONDITIONS[$node['pruefung']] ?? '';
                if ($node['pruefung'] === 'in_liste') {
                    $text = 'steht in der Liste „' . Lists::name((int) $node['list_id']) . '“';
                }
                return 'Wenn Empfänger ' . $text;
            case 'aktion':
                $text = self::ACTIONS[$node['aktion']] ?? '';
                if (str_starts_with((string) $node['aktion'], 'liste_')) {
                    $text .= ': ' . Lists::name((int) $node['list_id']);
                }
                return ucfirst($text);
            case 'ende':
                return 'Strecke endet hier';
        }
        return (string) $node['type'];
    }

    /* ------------------------------------------------------------- Prüfung */

    /**
     * Wertet eine Bedingung für einen Empfänger aus.
     *
     * @param array $node       der Bedingungsknoten
     * @param array $subscriber Empfänger
     * @param int   $automationId zum Auffinden der zuletzt gesendeten Mail
     */
    public static function evaluate(array $node, array $subscriber, int $automationId): bool
    {
        $subscriberId = (int) $subscriber['id'];

        switch ((string) $node['pruefung']) {
            case 'in_liste':
                $listId = (int) $node['list_id'];
                return $listId > 0 && in_array($listId, Subscribers::listIds($subscriberId), true);

            case 'hat_firma':
                return trim((string) ($subscriber['company'] ?? '')) !== '';

            case 'geoeffnet':
            case 'geklickt':
                $queueId = self::lastMailQueueId($automationId, $subscriberId);
                if ($queueId === 0) {
                    return false; // noch keine Mail dieser Strecke zugestellt
                }
                $typ = $node['pruefung'] === 'geklickt' ? Events::CLICK : Events::OPEN;
                return (int) DB::value(
                    'SELECT COUNT(*) FROM events WHERE queue_id = ? AND type = ?',
                    [$queueId, $typ]
                ) > 0;
        }
        return false;
    }

    /** Die zuletzt zugestellte Mail dieser Strecke an diesen Empfänger. */
    private static function lastMailQueueId(int $automationId, int $subscriberId): int
    {
        $stepIds = array_map('intval', DB::column(
            'SELECT id FROM automation_steps WHERE automation_id = ?',
            [$automationId]
        ));
        if ($stepIds === []) {
            return 0;
        }
        $platzhalter = implode(',', array_fill(0, count($stepIds), '?'));
        return (int) DB::value(
            "SELECT id FROM queue
             WHERE subscriber_id = ? AND status = 'sent' AND step_id IN ($platzhalter)
             ORDER BY sent_at DESC, id DESC LIMIT 1",
            array_merge([$subscriberId], $stepIds),
            0
        );
    }

    /* ------------------------------------------------------------- Hinweise */

    /**
     * Prüft einen Ablauf auf offensichtliche Lücken.
     * @return string[] Liste der Hinweise (leer = alles gut)
     */
    public static function problems(array $flow): array
    {
        $hinweise = [];
        $index    = self::index($flow);
        $mails    = 0;

        foreach ($index['nodes'] as $node) {
            if ($node['type'] === 'mail') {
                $mails++;
                $step = (int) $node['step_id'] > 0 ? Automations::step((int) $node['step_id']) : null;
                if ($step === null) {
                    $hinweise[] = 'Ein E-Mail-Schritt hat noch keinen Inhalt.';
                } elseif (trim((string) $step['subject']) === '') {
                    $hinweise[] = 'Einem E-Mail-Schritt fehlt der Betreff – er wird übersprungen.';
                }
            }
            if ($node['type'] === 'bedingung' && $node['pruefung'] === 'in_liste' && (int) $node['list_id'] === 0) {
                $hinweise[] = 'Bei einer Bedingung ist keine Liste ausgewählt.';
            }
            if ($node['type'] === 'aktion' && str_starts_with((string) $node['aktion'], 'liste_')
                && (int) $node['list_id'] === 0) {
                $hinweise[] = 'Bei einer Aktion ist keine Liste ausgewählt.';
            }
            if ($node['type'] === 'bedingung' && ($node['ja'] ?? []) === [] && ($node['nein'] ?? []) === []) {
                $hinweise[] = 'Eine Bedingung hat weder einen Ja- noch einen Nein-Zweig.';
            }
        }
        if ($mails === 0) {
            $hinweise[] = 'Der Ablauf verschickt noch keine E-Mail.';
        }
        return array_values(array_unique($hinweise));
    }
}
