<?php
/**
 * Abgrenzung zur Rechtsberatung.
 *
 * Steht auf allen Seiten, die regulatorische Anforderungen behandeln – NIS2,
 * DORA, ISO 27001, Meldepflichten, Verträge. Der Hinweis ist kein Kleingedrucktes,
 * sondern Teil der Positionierung: Ich baue Strukturen, Prozesse und Nachweise.
 * Ob eine Norm für ein Unternehmen gilt und was daraus rechtlich folgt, sagt
 * eine Kanzlei.
 *
 * Optional vorher setzen: $rechtshinweisText für eine seitenspezifische Fassung.
 */
$rechtshinweisText = $rechtshinweisText ?? 'Ob und in welchem Umfang eine Regulierung
    für Ihr Unternehmen gilt, ist eine Rechtsfrage. Diese Seite beschreibt den
    fachlichen und organisatorischen Rahmen, nicht die rechtliche Bewertung.';
?>
<div class="callout is-legal">
    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
    <div class="callout-body">
        <h3 class="callout-title">Keine Rechtsberatung</h3>
        <p><?= $rechtshinweisText ?>
        Ich unterstütze Sie dabei, Anforderungen organisatorisch umzusetzen, Prozesse
        aufzubauen und Nachweise zu erzeugen. Die rechtliche Bewertung – Betroffenheit,
        Haftung, Meldepflichten, Vertragsgestaltung – gehört in die Hände zugelassener
        Rechtsanwältinnen und Rechtsanwälte. Gern arbeite ich Ihrer Kanzlei fachlich zu
        oder empfehle spezialisierte Ansprechpartner.</p>
    </div>
</div>
<?php $rechtshinweisText = null; ?>
