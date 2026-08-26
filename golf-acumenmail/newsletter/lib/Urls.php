<?php
/**
 * Urls – alle öffentlichen Links des Systems an einer Stelle.
 * Links, die von außen aufgerufen werden, tragen eine Signatur,
 * damit sie nicht erraten oder durchprobiert werden können.
 */
final class Urls
{
    public static function confirm(string $subscriberToken): string
    {
        return Config::url('bestaetigen.php') . '?t=' . rawurlencode($subscriberToken);
    }

    public static function unsubscribe(string $subscriberToken, string $queueToken = ''): string
    {
        $url = Config::url('abmelden.php')
             . '?t=' . rawurlencode($subscriberToken)
             . '&s=' . Util::sign('unsub:' . $subscriberToken);
        if ($queueToken !== '') {
            $url .= '&q=' . rawurlencode($queueToken);
        }
        return $url;
    }

    public static function preferences(string $subscriberToken): string
    {
        return Config::url('einstellungen.php')
             . '?t=' . rawurlencode($subscriberToken)
             . '&s=' . Util::sign('pref:' . $subscriberToken);
    }

    public static function webview(?int $campaignId, string $queueToken = ''): string
    {
        if ($campaignId === null || $campaignId <= 0) {
            return Config::url('archiv.php');
        }
        $url = Config::url('archiv.php') . '?c=' . $campaignId;
        if ($queueToken !== '') {
            $url .= '&q=' . rawurlencode($queueToken);
        }
        return $url;
    }

    public static function archive(): string
    {
        return Config::url('archiv.php');
    }

    public static function openPixel(string $queueTokenPlaceholder): string
    {
        return Config::url('track.php') . '?o=' . $queueTokenPlaceholder;
    }

    public static function click(string $queueTokenPlaceholder, int $linkId): string
    {
        return Config::url('track.php') . '?c=' . $queueTokenPlaceholder . '&l=' . $linkId;
    }

    public static function signupPage(): string
    {
        return Config::url('anmelden.php');
    }
}
