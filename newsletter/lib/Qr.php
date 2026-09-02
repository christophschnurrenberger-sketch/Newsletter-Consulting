<?php
/**
 * Qr – erzeugt einen QR-Code als SVG.
 *
 * Gebraucht wird er an genau einer Stelle: damit man beim Einrichten der
 * Zwei-Faktor-Anmeldung das Geheimnis abfotografieren kann, statt 32
 * Zeichen abzutippen.
 *
 * Bewusst ohne Fremdbibliothek und ohne fremden Dienst: Ein QR-Code, der
 * das Anmeldegeheimnis enthält, darf diesen Server nicht verlassen.
 *
 * Umfang: Byte-Modus, Fehlerkorrektur M, Fassungen 1 bis 12 – das reicht
 * für Adressen bis 287 Zeichen und damit für jedes otpauth://.
 * Die Ausgabe ist Modul für Modul gegen eine Referenzbibliothek geprüft.
 */
final class Qr
{
    /** Datenwörter und Fehlerkorrektur je Fassung, Stufe M. */
    private const FASSUNGEN = [
        //          [Wörter gesamt, EC je Block, [Blockgrößen (Datenwörter)]]
        1  => [26,   10, [16]],
        2  => [44,   16, [28]],
        3  => [70,   26, [44]],
        4  => [100,  18, [32, 32]],
        5  => [134,  24, [43, 43]],
        6  => [172,  16, [27, 27, 27, 27]],
        7  => [196,  18, [31, 31, 31, 31]],
        8  => [242,  22, [38, 38, 39, 39]],
        9  => [292,  22, [36, 36, 36, 37, 37]],
        10 => [346,  26, [43, 43, 43, 43, 44]],
        11 => [404,  30, [50, 51, 51, 51, 51]],
        12 => [466,  22, [36, 36, 36, 36, 36, 36, 37, 37]],
    ];

    /** Mittelpunkte der Ausrichtungsmuster je Fassung. */
    private const AUSRICHTUNG = [
        1 => [], 2 => [6, 18], 3 => [6, 22], 4 => [6, 26], 5 => [6, 30], 6 => [6, 34],
        7 => [6, 22, 38], 8 => [6, 24, 42], 9 => [6, 26, 46], 10 => [6, 28, 50],
        11 => [6, 30, 54], 12 => [6, 32, 58],
    ];

    /**
     * QR-Code als SVG.
     *
     * @param  string   $text  Der Inhalt
     * @param  int      $rand  Ruhezone in Modulen (Norm: 4)
     * @param  int|null $maske Nur zum Prüfen – sonst wird die beste gewählt
     * @throws RuntimeException wenn der Text nicht hineinpasst
     */
    public static function svg(string $text, int $groesse = 220, int $rand = 4, ?int $maske = null): string
    {
        $matrix = self::matrix($text, $maske);
        $n      = count($matrix);
        $gesamt = $n + 2 * $rand;

        // Ein einziger Pfad für alle schwarzen Module – kurz und schnell.
        $pfad = '';
        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($matrix[$y][$x] === 1) {
                    $pfad .= 'M' . ($x + $rand) . ' ' . ($y + $rand) . 'h1v1h-1z';
                }
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $groesse . '" height="' . $groesse . '"'
             . ' viewBox="0 0 ' . $gesamt . ' ' . $gesamt . '" shape-rendering="crispEdges"'
             . ' role="img" aria-label="QR-Code zum Einrichten der Zwei-Faktor-Anmeldung">'
             . '<rect width="' . $gesamt . '" height="' . $gesamt . '" fill="#FFFFFF"/>'
             . '<path d="' . $pfad . '" fill="#000000"/>'
             . '</svg>';
    }

    /**
     * Die fertige Modulmatrix: 1 = schwarz, 0 = weiß.
     *
     * @return array<int,array<int,int>>
     */
    public static function matrix(string $text, ?int $maske = null): array
    {
        $laenge  = strlen($text);
        $fassung = self::fassungFuer($laenge);
        [$woerterGesamt, $ecJeBlock, $bloecke] = self::FASSUNGEN[$fassung];

        $daten     = self::datenwoerter($text, $fassung, $woerterGesamt, $ecJeBlock, $bloecke);
        $groesse   = 17 + 4 * $fassung;
        $reserviert = self::grundraster($fassung, $groesse, $roh);

        self::datenLegen($roh, $reserviert, $groesse, $daten);

        $beste = $maske;
        if ($beste === null) {
            $beste = 0;
            $strafe = PHP_INT_MAX;
            for ($m = 0; $m < 8; $m++) {
                $versuch = self::maskeAnwenden($roh, $reserviert, $groesse, $m);
                self::formatSchreiben($versuch, $groesse, $m);
                $wert = self::strafpunkte($versuch, $groesse);
                if ($wert < $strafe) {
                    $strafe = $wert;
                    $beste  = $m;
                }
            }
        }

        $fertig = self::maskeAnwenden($roh, $reserviert, $groesse, $beste);
        self::formatSchreiben($fertig, $groesse, $beste);
        if ($fassung >= 7) {
            self::fassungSchreiben($fertig, $groesse, $fassung);
        }
        return $fertig;
    }

    /* ------------------------------------------------------------- Daten */

    private static function fassungFuer(int $laenge): int
    {
        foreach (self::FASSUNGEN as $fassung => [$woerterGesamt, $ecJeBlock, $bloecke]) {
            $datenwoerter = array_sum($bloecke);
            // Modus (4 Bit) + Längenangabe (8 oder 16 Bit) müssen mit hinein
            $kopfBits = 4 + ($fassung <= 9 ? 8 : 16);
            if ($laenge * 8 + $kopfBits <= $datenwoerter * 8) {
                return $fassung;
            }
        }
        throw new RuntimeException('Der Text ist zu lang für einen QR-Code dieser Größe.');
    }

    /**
     * Bitstrom bauen, in Blöcke teilen, Fehlerkorrektur anhängen, verweben.
     *
     * @param  int[] $bloecke
     * @return int[] Die Wörter in der Reihenfolge, in der sie ins Bild kommen
     */
    private static function datenwoerter(string $text, int $fassung, int $woerterGesamt,
                                         int $ecJeBlock, array $bloecke): array
    {
        $datenwoerter = array_sum($bloecke);

        $bits = '0100';                                   // Byte-Modus
        $bits .= str_pad(decbin(strlen($text)), $fassung <= 9 ? 8 : 16, '0', STR_PAD_LEFT);
        for ($i = 0, $n = strlen($text); $i < $n; $i++) {
            $bits .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }

        // Abschluss: bis zu vier Nullen, dann auf volle Bytes auffüllen
        $bits .= str_repeat('0', min(4, $datenwoerter * 8 - strlen($bits)));
        if (strlen($bits) % 8 !== 0) {
            $bits .= str_repeat('0', 8 - strlen($bits) % 8);
        }

        $woerter = [];
        foreach (str_split($bits, 8) as $byte) {
            $woerter[] = bindec($byte);
        }
        // Auffüllwörter, abwechselnd – so schreibt es die Norm vor
        $fueller = [0xEC, 0x11];
        $i = 0;
        while (count($woerter) < $datenwoerter) {
            $woerter[] = $fueller[$i++ % 2];
        }

        // In Blöcke teilen und je Block die Fehlerkorrektur rechnen
        $datenBloecke = [];
        $ecBloecke    = [];
        $pos = 0;
        foreach ($bloecke as $groesse) {
            $block          = array_slice($woerter, $pos, $groesse);
            $pos           += $groesse;
            $datenBloecke[] = $block;
            $ecBloecke[]    = self::fehlerkorrektur($block, $ecJeBlock);
        }

        // Verweben: erst spaltenweise die Daten, dann spaltenweise die EC-Wörter
        $aus = [];
        $max = max(array_map('count', $datenBloecke));
        for ($i = 0; $i < $max; $i++) {
            foreach ($datenBloecke as $block) {
                if (isset($block[$i])) {
                    $aus[] = $block[$i];
                }
            }
        }
        for ($i = 0; $i < $ecJeBlock; $i++) {
            foreach ($ecBloecke as $block) {
                $aus[] = $block[$i];
            }
        }

        // Restbits, falls die Fassung mehr Platz hat als Wörter
        while (count($aus) < $woerterGesamt) {
            $aus[] = 0;
        }
        return $aus;
    }

    /**
     * Reed-Solomon über GF(256).
     *
     * @param  int[] $daten
     * @return int[]
     */
    private static function fehlerkorrektur(array $daten, int $anzahl): array
    {
        [$log, $exp] = self::galois();

        // Generatorpolynom aufbauen: (x - a^0)(x - a^1)…
        $gen = [1];
        for ($i = 0; $i < $anzahl; $i++) {
            $neu = array_fill(0, count($gen) + 1, 0);
            foreach ($gen as $k => $wert) {
                $neu[$k]     ^= $wert;
                $neu[$k + 1] ^= $wert === 0 ? 0 : $exp[($log[$wert] + $i) % 255];
            }
            $gen = $neu;
        }

        $rest = array_merge($daten, array_fill(0, $anzahl, 0));
        for ($i = 0, $n = count($daten); $i < $n; $i++) {
            $fuehrend = $rest[$i];
            if ($fuehrend === 0) {
                continue;
            }
            $faktor = $log[$fuehrend];
            foreach ($gen as $k => $wert) {
                if ($wert !== 0) {
                    $rest[$i + $k] ^= $exp[($log[$wert] + $faktor) % 255];
                }
            }
        }
        return array_slice($rest, count($daten));
    }

    /** @return array{0:int[],1:int[]} Logarithmus- und Exponententabelle */
    private static function galois(): array
    {
        static $log = null, $exp = null;
        if ($log !== null) {
            return [$log, $exp];
        }
        $log = array_fill(0, 256, 0);
        $exp = array_fill(0, 256, 0);
        $x = 1;
        for ($i = 0; $i < 255; $i++) {
            $exp[$i] = $x;
            $log[$x] = $i;
            $x <<= 1;
            if ($x & 0x100) {
                $x ^= 0x11D;   // das Polynom der QR-Norm
            }
        }
        return [$log, $exp];
    }

    /* ------------------------------------------------------------- Bild */

    /**
     * Sucher, Zeitmuster und Ausrichtung setzen.
     *
     * @param  array<int,array<int,int>>|null $roh wird gefüllt
     * @return array<int,array<int,bool>> true = feste Stelle, keine Daten
     */
    private static function grundraster(int $fassung, int $groesse, ?array &$roh): array
    {
        $roh        = array_fill(0, $groesse, array_fill(0, $groesse, 0));
        $reserviert = array_fill(0, $groesse, array_fill(0, $groesse, false));

        $sucher = static function (int $sx, int $sy) use (&$roh, &$reserviert, $groesse): void {
            for ($y = -1; $y <= 7; $y++) {
                for ($x = -1; $x <= 7; $x++) {
                    $px = $sx + $x;
                    $py = $sy + $y;
                    if ($px < 0 || $py < 0 || $px >= $groesse || $py >= $groesse) {
                        continue;
                    }
                    $imRing  = ($x === 0 || $x === 6) && $y >= 0 && $y <= 6;
                    $imRing  = $imRing || (($y === 0 || $y === 6) && $x >= 0 && $x <= 6);
                    $imKern  = $x >= 2 && $x <= 4 && $y >= 2 && $y <= 4;
                    $roh[$py][$px]        = ($imRing || $imKern) ? 1 : 0;
                    $reserviert[$py][$px] = true;
                }
            }
        };
        $sucher(0, 0);
        $sucher($groesse - 7, 0);
        $sucher(0, $groesse - 7);

        // Zeitmuster
        for ($i = 8; $i < $groesse - 8; $i++) {
            $wert = $i % 2 === 0 ? 1 : 0;
            $roh[6][$i] = $wert;  $reserviert[6][$i] = true;
            $roh[$i][6] = $wert;  $reserviert[$i][6] = true;
        }

        // Ausrichtungsmuster – nicht dort, wo schon ein Sucher sitzt
        $punkte = self::AUSRICHTUNG[$fassung];
        foreach ($punkte as $cy) {
            foreach ($punkte as $cx) {
                $beiSucher = ($cx <= 8 && $cy <= 8)
                          || ($cx >= $groesse - 9 && $cy <= 8)
                          || ($cx <= 8 && $cy >= $groesse - 9);
                if ($beiSucher) {
                    continue;
                }
                for ($y = -2; $y <= 2; $y++) {
                    for ($x = -2; $x <= 2; $x++) {
                        $rand = max(abs($x), abs($y));
                        $roh[$cy + $y][$cx + $x]        = ($rand === 1) ? 0 : 1;
                        $reserviert[$cy + $y][$cx + $x] = true;
                    }
                }
            }
        }

        // Immer schwarz, direkt über dem linken unteren Sucher
        $roh[$groesse - 8][8]        = 1;
        $reserviert[$groesse - 8][8] = true;

        // Plätze für die Formatangaben freihalten
        for ($i = 0; $i < 9; $i++) {
            if (!$reserviert[8][$i]) { $reserviert[8][$i] = true; }
            if (!$reserviert[$i][8]) { $reserviert[$i][8] = true; }
        }
        for ($i = 0; $i < 8; $i++) {
            $reserviert[8][$groesse - 1 - $i] = true;
            $reserviert[$groesse - 1 - $i][8] = true;
        }

        // Und für die Fassungsangabe ab Fassung 7
        if ($fassung >= 7) {
            for ($i = 0; $i < 6; $i++) {
                for ($k = 0; $k < 3; $k++) {
                    $reserviert[$groesse - 11 + $k][$i] = true;
                    $reserviert[$i][$groesse - 11 + $k] = true;
                }
            }
        }

        return $reserviert;
    }

    /**
     * Die Wörter im Zickzack von rechts unten nach oben legen.
     *
     * @param array<int,array<int,int>>  $roh
     * @param array<int,array<int,bool>> $reserviert
     * @param int[]                      $woerter
     */
    private static function datenLegen(array &$roh, array $reserviert, int $groesse, array $woerter): void
    {
        $bits = '';
        foreach ($woerter as $wort) {
            $bits .= str_pad(decbin($wort), 8, '0', STR_PAD_LEFT);
        }

        $stelle = 0;
        $aufwaerts = true;
        for ($rechts = $groesse - 1; $rechts > 0; $rechts -= 2) {
            if ($rechts === 6) {
                $rechts = 5;   // die senkrechte Zeitspur wird übersprungen
            }
            for ($z = 0; $z < $groesse; $z++) {
                $y = $aufwaerts ? $groesse - 1 - $z : $z;
                for ($s = 0; $s < 2; $s++) {
                    $x = $rechts - $s;
                    if ($reserviert[$y][$x]) {
                        continue;
                    }
                    $roh[$y][$x] = $stelle < strlen($bits) ? (int) $bits[$stelle] : 0;
                    $stelle++;
                }
            }
            $aufwaerts = !$aufwaerts;
        }
    }

    /**
     * @param  array<int,array<int,int>>  $roh
     * @param  array<int,array<int,bool>> $reserviert
     * @return array<int,array<int,int>>
     */
    private static function maskeAnwenden(array $roh, array $reserviert, int $groesse, int $maske): array
    {
        for ($y = 0; $y < $groesse; $y++) {
            for ($x = 0; $x < $groesse; $x++) {
                if ($reserviert[$y][$x]) {
                    continue;
                }
                $trifft = match ($maske) {
                    0 => ($y + $x) % 2 === 0,
                    1 => $y % 2 === 0,
                    2 => $x % 3 === 0,
                    3 => ($y + $x) % 3 === 0,
                    4 => (intdiv($y, 2) + intdiv($x, 3)) % 2 === 0,
                    5 => (($y * $x) % 2) + (($y * $x) % 3) === 0,
                    6 => ((($y * $x) % 2) + (($y * $x) % 3)) % 2 === 0,
                    default => ((($y + $x) % 2) + (($y * $x) % 3)) % 2 === 0,
                };
                if ($trifft) {
                    $roh[$y][$x] ^= 1;
                }
            }
        }
        return $roh;
    }

    /** @param array<int,array<int,int>> $matrix */
    private static function formatSchreiben(array &$matrix, int $groesse, int $maske): void
    {
        // Stufe M ist 00, dahinter die drei Bit der Maske
        $wert = (0b00 << 3) | $maske;
        $rest = $wert << 10;
        for ($i = 4; $i >= 0; $i--) {
            if ($rest & (1 << ($i + 10))) {
                $rest ^= 0b10100110111 << $i;
            }
        }
        $bits = (($wert << 10) | $rest) ^ 0b101010000010010;

        for ($i = 0; $i < 15; $i++) {
            // Höchstwertiges Bit zuerst: Die Formatangabe wird von links
            // nach rechts gelesen, nicht wie die Fassungsangabe darunter.
            $bit = ($bits >> (14 - $i)) & 1;
            // Erste Kopie: um den linken oberen Sucher herum
            if ($i < 6)       { $matrix[8][$i] = $bit; }
            elseif ($i === 6) { $matrix[8][7] = $bit; }
            elseif ($i === 7) { $matrix[8][8] = $bit; }
            elseif ($i === 8) { $matrix[7][8] = $bit; }
            else              { $matrix[14 - $i][8] = $bit; }

            /*
             * Zweite Kopie: sieben Module senkrecht über dem linken unteren
             * Sucher, acht waagerecht neben dem rechten oberen. Nicht acht
             * und sieben – sonst überschriebe das achte das Modul, das immer
             * schwarz bleiben muss.
             */
            if ($i < 7) { $matrix[$groesse - 1 - $i][8] = $bit; }
            else        { $matrix[8][$groesse - 15 + $i] = $bit; }
        }
    }

    /** @param array<int,array<int,int>> $matrix */
    private static function fassungSchreiben(array &$matrix, int $groesse, int $fassung): void
    {
        $rest = $fassung << 12;
        for ($i = 5; $i >= 0; $i--) {
            if ($rest & (1 << ($i + 12))) {
                $rest ^= 0b1111100100101 << $i;
            }
        }
        $bits = ($fassung << 12) | $rest;

        for ($i = 0; $i < 18; $i++) {
            $bit = ($bits >> $i) & 1;
            $a = intdiv($i, 3);
            $b = $groesse - 11 + ($i % 3);
            $matrix[$b][$a] = $bit;
            $matrix[$a][$b] = $bit;
        }
    }

    /**
     * Wie unruhig sieht das Bild aus? Kleiner ist besser.
     *
     * @param array<int,array<int,int>> $m
     */
    private static function strafpunkte(array $m, int $groesse): int
    {
        $punkte = 0;

        // 1) Reihen gleicher Farbe ab Länge 5
        for ($d = 0; $d < 2; $d++) {
            for ($a = 0; $a < $groesse; $a++) {
                $lauf = 1;
                for ($b = 1; $b < $groesse; $b++) {
                    $jetzt  = $d === 0 ? $m[$a][$b]     : $m[$b][$a];
                    $vorher = $d === 0 ? $m[$a][$b - 1] : $m[$b - 1][$a];
                    if ($jetzt === $vorher) {
                        $lauf++;
                        continue;
                    }
                    if ($lauf >= 5) { $punkte += 3 + ($lauf - 5); }
                    $lauf = 1;
                }
                if ($lauf >= 5) { $punkte += 3 + ($lauf - 5); }
            }
        }

        // 2) Gleichfarbige Zweier-Quadrate
        for ($y = 0; $y < $groesse - 1; $y++) {
            for ($x = 0; $x < $groesse - 1; $x++) {
                $w = $m[$y][$x];
                if ($w === $m[$y][$x + 1] && $w === $m[$y + 1][$x] && $w === $m[$y + 1][$x + 1]) {
                    $punkte += 3;
                }
            }
        }

        // 3) Muster, das dem Sucher ähnelt
        $suche = [1, 0, 1, 1, 1, 0, 1, 0, 0, 0, 0];
        for ($d = 0; $d < 2; $d++) {
            for ($a = 0; $a < $groesse; $a++) {
                for ($b = 0; $b <= $groesse - 11; $b++) {
                    $treffer = true;
                    $rueck   = true;
                    for ($k = 0; $k < 11; $k++) {
                        $wert = $d === 0 ? $m[$a][$b + $k] : $m[$b + $k][$a];
                        if ($wert !== $suche[$k])      { $treffer = false; }
                        if ($wert !== $suche[10 - $k]) { $rueck   = false; }
                    }
                    if ($treffer) { $punkte += 40; }
                    if ($rueck)   { $punkte += 40; }
                }
            }
        }

        // 4) Abweichung vom halb-halb-Verhältnis
        $schwarz = 0;
        foreach ($m as $zeile) {
            $schwarz += array_sum($zeile);
        }
        $anteil  = $schwarz * 100 / ($groesse * $groesse);
        $punkte += (int) (abs($anteil - 50) / 5) * 10;

        return $punkte;
    }
}
