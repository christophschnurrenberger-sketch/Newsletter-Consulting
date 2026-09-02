<?php
/**
 * Totp – der zweite Faktor beim Anmelden (RFC 6238).
 *
 * Die App auf dem Telefon und der Server kennen dasselbe Geheimnis. Beide
 * rechnen daraus alle 30 Sekunden dieselbe sechsstellige Zahl. Wer nur das
 * Passwort hat, kommt damit nicht hinein – die Zahl steht auf dem Telefon.
 *
 * Bewusst ohne Fremdbibliothek: Es sind keine hundert Zeilen, und alles,
 * was hier gebraucht wird (HMAC-SHA1, Zufall), bringt PHP selbst mit.
 * Geprüft ist die Rechnung gegen die Testwerte aus RFC 6238.
 */
final class Totp
{
    /** Länge eines Zeitfensters in Sekunden – 30 ist der Standard aller Apps. */
    public const SCHRITT = 30;

    /** Wie viele Fenster vor und nach dem aktuellen noch gelten. */
    private const TOLERANZ = 1;

    private const BASIS32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /* ------------------------------------------------------------ Geheimnis */

    /** Ein neues Geheimnis: 20 Byte Zufall, als Basis32 lesbar gemacht. */
    public static function neuesGeheimnis(): string
    {
        return self::base32Kodieren(random_bytes(20));
    }

    /** In Vierergruppen – so lässt es sich abtippen, ohne sich zu verzählen. */
    public static function lesbar(string $geheimnis): string
    {
        return trim(chunk_split($geheimnis, 4, ' '));
    }

    public static function base32Kodieren(string $binaer): string
    {
        $bits = '';
        for ($i = 0, $n = strlen($binaer); $i < $n; $i++) {
            $bits .= str_pad(decbin(ord($binaer[$i])), 8, '0', STR_PAD_LEFT);
        }
        $aus = '';
        foreach (str_split($bits, 5) as $fuenf) {
            $aus .= self::BASIS32[bindec(str_pad($fuenf, 5, '0', STR_PAD_RIGHT))];
        }
        return $aus;
    }

    public static function base32Dekodieren(string $text): string
    {
        $text = strtoupper(preg_replace('/[^A-Za-z2-7]/', '', $text) ?? '');
        $bits = '';
        for ($i = 0, $n = strlen($text); $i < $n; $i++) {
            $wert = strpos(self::BASIS32, $text[$i]);
            if ($wert === false) {
                continue;
            }
            $bits .= str_pad(decbin($wert), 5, '0', STR_PAD_LEFT);
        }
        $aus = '';
        foreach (str_split($bits, 8) as $acht) {
            if (strlen($acht) === 8) {
                $aus .= chr(bindec($acht));
            }
        }
        return $aus;
    }

    /* ----------------------------------------------------------- Die Zahl */

    /**
     * Die Zahl für ein Zeitfenster.
     *
     * @param string   $geheimnis Basis32 wie in der App hinterlegt
     * @param int|null $zeit      Unix-Zeit; null = jetzt
     */
    public static function code(string $geheimnis, ?int $zeit = null, int $stellen = 6): string
    {
        $schluessel = self::base32Dekodieren($geheimnis);
        if ($schluessel === '') {
            return '';
        }
        $fenster = intdiv($zeit ?? time(), self::SCHRITT);

        // Die Fensternummer als 8 Byte, höchstwertiges Byte zuerst
        $daten = '';
        for ($i = 7; $i >= 0; $i--) {
            $daten .= chr(($fenster >> ($i * 8)) & 0xFF);
        }

        $hash = hash_hmac('sha1', $daten, $schluessel, true);
        // Die letzten vier Bit sagen, wo im Hash die Zahl steht
        $stelle = ord($hash[19]) & 0x0F;
        $zahl   = ((ord($hash[$stelle]) & 0x7F) << 24)
                | ((ord($hash[$stelle + 1]) & 0xFF) << 16)
                | ((ord($hash[$stelle + 2]) & 0xFF) << 8)
                |  (ord($hash[$stelle + 3]) & 0xFF);

        return str_pad((string) ($zahl % (10 ** $stellen)), $stellen, '0', STR_PAD_LEFT);
    }

    /**
     * Stimmt die eingetippte Zahl?
     *
     * Geprüft wird auch das Fenster davor und danach: Uhren gehen selten
     * genau gleich, und zwischen Ablesen und Abschicken vergehen Sekunden.
     */
    public static function pruefe(string $geheimnis, string $eingabe): bool
    {
        $eingabe = preg_replace('/\D/', '', $eingabe) ?? '';
        if (strlen($eingabe) !== 6 || $geheimnis === '') {
            return false;
        }
        $jetzt = time();
        for ($v = -self::TOLERANZ; $v <= self::TOLERANZ; $v++) {
            if (hash_equals(self::code($geheimnis, $jetzt + $v * self::SCHRITT), $eingabe)) {
                return true;
            }
        }
        return false;
    }

    /* --------------------------------------------------------- Einrichtung */

    /**
     * Die Adresse, die im QR-Code steckt. Jede Authenticator-App versteht sie.
     */
    public static function adresse(string $geheimnis, string $konto, string $herausgeber): string
    {
        $herausgeber = trim($herausgeber) !== '' ? trim($herausgeber) : 'Newsletter';
        return 'otpauth://totp/' . rawurlencode($herausgeber) . ':' . rawurlencode($konto)
             . '?secret=' . $geheimnis
             . '&issuer=' . rawurlencode($herausgeber)
             . '&algorithm=SHA1&digits=6&period=' . self::SCHRITT;
    }

    /* -------------------------------------------------- Ersatzcodes */

    /**
     * Codes für den Fall, dass das Telefon weg ist.
     *
     * @return string[] Klartext – wird genau einmal angezeigt
     */
    public static function ersatzcodes(int $anzahl = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $anzahl; $i++) {
            // Ohne I, O, 0, 1 – die verwechselt man beim Abschreiben
            $zeichen = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $code    = '';
            for ($z = 0; $z < 10; $z++) {
                $code .= $zeichen[random_int(0, strlen($zeichen) - 1)];
            }
            $codes[] = substr($code, 0, 5) . '-' . substr($code, 5);
        }
        return $codes;
    }

    /**
     * Ersatzcodes werden nicht im Klartext gespeichert.
     *
     * Sie sind lang und rein zufällig, deshalb genügt hier ein HMAC mit dem
     * Schlüssel der Installation – es gibt nichts zu erraten, was ein
     * langsames Verfahren wie bcrypt aufhalten müsste.
     */
    public static function codeHash(string $code): string
    {
        $sauber = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $code) ?? '');
        return hash_hmac('sha256', $sauber, Config::secret());
    }

    /**
     * Prüft einen Ersatzcode und streicht ihn.
     *
     * @param  string[] $hashes Die gespeicherten Hashes
     * @return string[]|null    Die verbleibenden Hashes, oder null bei Fehlschlag
     */
    public static function ersatzcodeEinloesen(array $hashes, string $eingabe): ?array
    {
        $gesucht = self::codeHash($eingabe);
        $rest    = [];
        $treffer = false;
        foreach ($hashes as $hash) {
            if (!$treffer && hash_equals((string) $hash, $gesucht)) {
                $treffer = true;
                continue;
            }
            $rest[] = (string) $hash;
        }
        return $treffer ? $rest : null;
    }
}
