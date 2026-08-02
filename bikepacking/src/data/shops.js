'use strict';

/**
 * Ziel-URLs für Produktempfehlungen.
 *
 * Bewusst als **Kategorie- bzw. Suchlinks** angelegt, nicht als Deeplinks auf
 * einzelne Artikelnummern: Artikelnummern verschwinden, Kategorien bleiben.
 * Damit veraltet keine Empfehlung, und es entstehen keine toten Links.
 *
 * ANPASSEN NACH FREISCHALTUNG DEINER PARTNERPROGRAMME:
 * Ersetze die URLs hier durch die Kategorieseiten deiner Partnershops
 * (bike-components, Bergfreunde, ROSE …). Die Partner-IDs selbst stehen in
 * src/config.js – hier stehen nur die Ziele.
 */

const amazon = (query) => `https://www.amazon.de/s?k=${encodeURIComponent(query)}`;

const shops = {
  // Taschen
  satteltasche: amazon('bikepacking satteltasche seatpack wasserdicht'),
  satteltascheKlein: amazon('satteltasche fahrrad 8 liter bikepacking'),
  lenkerrolle: amazon('bikepacking lenkerrolle handlebar bag'),
  lenkertascheKlein: amazon('fahrrad lenkertasche wasserdicht bikepacking'),
  rahmentasche: amazon('bikepacking rahmentasche fahrrad'),
  rahmentascheHalb: amazon('halbrahmentasche fahrrad wasserdicht'),
  oberrohrtasche: amazon('oberrohrtasche fahrrad top tube bag'),
  stemtasche: amazon('stem bag fahrrad lenkertasche flasche'),
  gabeltasche: amazon('gabeltasche fahrrad anything cage'),
  anythingCage: amazon('anything cage gabelhalter fahrrad'),
  packtaschen: amazon('fahrrad packtaschen hinterrad wasserdicht paar'),
  gepaecktraeger: amazon('gepäckträger fahrrad hinten stabil'),
  packsaecke: amazon('packsack wasserdicht dry bag set'),
  lenkerspacer: amazon('lenkertaschen spacer distanzhalter bikepacking'),

  // Schlafen
  zelt1p: amazon('1 personen zelt leicht trekking'),
  zelt2p: amazon('2 personen zelt leicht trekking'),
  tarp: amazon('tarp zelt leicht trekking'),
  biwaksack: amazon('biwaksack wasserdicht atmungsaktiv'),
  schlafsackSommer: amazon('schlafsack leicht sommer 10 grad'),
  schlafsack3jz: amazon('schlafsack daune 3 jahreszeiten leicht'),
  quilt: amazon('quilt schlafsack ultraleicht'),
  isomatte: amazon('isomatte aufblasbar leicht r-wert'),
  isomatteWinter: amazon('isomatte r-wert 4 winter aufblasbar'),
  kissen: amazon('reisekissen aufblasbar trekking'),

  // Kueche
  gaskocher: amazon('gaskocher schraubkocher trekking'),
  spirituskocher: amazon('spirituskocher trekking set'),
  topfSet: amazon('topf titan trekking 750 ml'),
  wasserfilter: amazon('wasserfilter outdoor trekking'),
  trinkflasche: amazon('fahrrad trinkflasche 750 ml'),
  faltflasche: amazon('faltflasche wasser 1 liter outdoor'),

  // Kleidung
  radhose: amazon('radhose herren sitzpolster lang'),
  radhoseDamen: amazon('radhose damen sitzpolster'),
  merinoShirt: amazon('merino shirt fahrrad kurzarm'),
  regenjacke: amazon('fahrrad regenjacke wasserdicht atmungsaktiv'),
  regenhose: amazon('regenhose fahrrad wasserdicht'),
  isolationsjacke: amazon('daunenjacke leicht packbar'),
  handschuhe: amazon('fahrradhandschuhe kurz gel'),
  buff: amazon('multifunktionstuch fahrrad'),

  // Technik
  frontlicht: amazon('fahrrad frontlicht akku stvzo'),
  ruecklicht: amazon('fahrrad rücklicht akku stvzo'),
  stirnlampe: amazon('stirnlampe leicht outdoor'),
  powerbank10: amazon('powerbank 10000 mah leicht'),
  powerbank20: amazon('powerbank 20000 mah usb c'),
  nabendynamo: amazon('nabendynamo laufrad 28 zoll'),
  usbLader: amazon('nabendynamo usb lader fahrrad'),
  gpsComputer: amazon('fahrradcomputer gps navigation'),
  handyhalter: amazon('handyhalterung fahrrad stabil'),

  // Werkzeug
  multitool: amazon('fahrrad multitool kettennieter'),
  minipumpe: amazon('mini luftpumpe fahrrad hochdruck'),
  schlauch: amazon('fahrradschlauch ersatz'),
  flickzeug: amazon('flickzeug fahrrad selbstklebend'),
  kettenschloss: amazon('kettenschloss fahrrad 11 fach'),
  tubelessKit: amazon('tubeless reparatur set fahrrad'),
  schloss: amazon('faltschloss fahrrad leicht'),
  kettenoel: amazon('kettenöl fahrrad trocken'),

  // Sonstiges
  ersteHilfe: amazon('erste hilfe set outdoor klein'),
  sitzcreme: amazon('sitzcreme radfahren'),
  packsackKlein: amazon('packbeutel set leicht outdoor'),
  handtuch: amazon('mikrofaser handtuch klein reise'),
  karte: amazon('radkarte deutschland fernradweg'),
};

module.exports = { shops, amazon };
