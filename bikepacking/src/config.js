'use strict';

/**
 * Zentrale Konfiguration der Website.
 *
 * Hier stehen alle Werte, die beim Livegang angepasst werden muessen.
 * Alles, was mit "DEIN_" oder "BITTE_" beginnt, ist ein Platzhalter.
 */

const site = {
  name: 'Sattelfest',
  claim: 'Bikepacking von der ersten Tour an',
  // Ohne abschliessenden Slash. Wird fuer canonical, Open Graph und sitemap.xml genutzt.
  origin: 'https://www.sattelfest.de',
  locale: 'de_DE',
  lang: 'de',
  themeColor: '#17303F',
  defaultDescription:
    'Sattelfest ist der Einstiegs-Guide fürs Bikepacking: welche Taschen du wirklich brauchst, welche Ausrüstung mitkommt, welche Routen sich für die erste Mehrtagestour eignen – und was du getrost zu Hause lässt.',
  // Erscheint im Impressum und in strukturierten Daten.
  publisher: 'BITTE_BETREIBERNAME_EINTRAGEN',
  contactEmail: 'BITTE_E_MAIL_EINTRAGEN',
};

/**
 * Veroeffentlichung auf dem eigenen Webspace.
 */
const deploy = {
  // Zielordner des Builds. Dessen INHALT wird auf den Webspace geladen.
  outDir: 'dist',

  /**
   * Art der internen Verlinkung.
   *
   *   'relative'  → ../assets/site.css   (Vorgabe, empfohlen)
   *                 Funktioniert per Doppelklick ueber file://, an der
   *                 Domain-Wurzel und in jedem Unterverzeichnis – ohne
   *                 dass basePath gesetzt werden muss.
   *
   *   'absolute'  → /assets/site.css
   *                 Klassische absolute Pfade. Dann muss basePath stimmen,
   *                 und ein Vorschauen per Doppelklick ist nicht moeglich.
   */
  paths: 'relative',

  /**
   * Nur bei paths: 'absolute' relevant.
   * Unterverzeichnis, in dem die Seite auf dem Webspace liegt.
   *
   *   ''              → Seite liegt direkt unter www.deine-domain.de
   *   '/bikepacking'  → Seite liegt unter www.deine-domain.de/bikepacking
   *
   * Ohne abschliessenden Slash.
   */
  basePath: '',

  // Erzeugt eine .htaccess mit Komprimierung, Caching und Sicherheits-Headern.
  // Auf Apache-Webspace (die meisten Anbieter) sinnvoll, auf nginx wirkungslos.
  writeHtaccess: true,
};

/** Vollstaendige URL einer Seite – fuer canonical, Open Graph und sitemap.xml. */
function absoluteUrl(href) {
  return site.origin + deploy.basePath + href;
}

/**
 * Newsletter-Anbindung.
 *
 * Die Seite ist rein statisch. Der Formular-Endpunkt muss auf einen
 * Double-Opt-in-faehigen Dienst zeigen (CleverReach, Brevo, MailerLite,
 * Rapidmail ...). Solange hier der Platzhalter steht, zeigt das Formular
 * einen Hinweis statt zu senden.
 */
const newsletter = {
  action: 'BITTE_NEWSLETTER_ENDPUNKT_EINTRAGEN',
  // Feldnamen des jeweiligen Anbieters
  emailField: 'email',
  nameField: 'first_name',
  leadMagnet: 'Die Sattelfest-Packliste als PDF (mit Gewichtsspalte)',
};

/**
 * Affiliate-Konfiguration.
 *
 * Es werden bewusst keine Deeplinks auf einzelne Artikelnummern gesetzt:
 * Artikelnummern und Preise veralten, Kategorielinks nicht. Trage hier deine
 * echten Partner-IDs bzw. Netzwerk-Deeplinks ein.
 *
 * `template` enthaelt {{url}} (die Ziel-URL, URL-kodiert) und wird vom
 * Link-Builder in src/components.js ausgewertet. Wer direkt mit Partner-Tags
 * arbeitet, nutzt stattdessen `suffix`.
 */
const affiliates = {
  // Globaler Schalter: solange false, werden alle Partnerlinks als normale
  // Links ohne Tracking ausgegeben (nuetzlich vor der Programm-Freischaltung).
  enabled: true,

  partners: {
    amazon: {
      label: 'Amazon',
      // Amazon PartnerNet: eigenes Tag eintragen, z. B. 'sattelfest-21'
      tag: 'DEIN-AMAZON-TAG-21',
      base: 'https://www.amazon.de/s',
      suffix: '?tag=DEIN-AMAZON-TAG-21',
    },
    bikecomponents: {
      label: 'bike-components',
      // z. B. Awin-Deeplink: https://www.awin1.com/cread.php?awinmid=XXXX&awinaffid=DEINE_ID&ued={{url}}
      template: 'BITTE_DEEPLINK_TEMPLATE_EINTRAGEN',
      base: 'https://www.bike-components.de',
    },
    bergfreunde: {
      label: 'Bergfreunde',
      template: 'BITTE_DEEPLINK_TEMPLATE_EINTRAGEN',
      base: 'https://www.bergfreunde.de',
    },
    rosebikes: {
      label: 'ROSE Bikes',
      template: 'BITTE_DEEPLINK_TEMPLATE_EINTRAGEN',
      base: 'https://www.rosebikes.de',
    },
  },

  // Pflichtangaben nach dem UWG bzw. der Preisangabenverordnung.
  disclosureShort:
    'Anzeige: Die mit * markierten Links sind Partnerlinks. Kaufst du darüber, erhalten wir eine Provision. Für dich ändert sich der Preis nicht.',
  disclosureLong:
    'Alle mit einem Sternchen (*) gekennzeichneten Links sind Provisions- bzw. Partnerlinks. Wenn du über einen solchen Link etwas kaufst, erhält Sattelfest eine Vergütung vom jeweiligen Händler. Der Preis für dich bleibt exakt gleich. Preise und Verfügbarkeiten können sich jederzeit ändern – maßgeblich ist immer die Anzeige im Shop zum Zeitpunkt des Kaufs.',
};

module.exports = { site, deploy, newsletter, affiliates, absoluteUrl };
