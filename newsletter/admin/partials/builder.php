<?php
/**
 * Oberfläche des Baukastens – wird von kampagne.php und vorlagen.php genutzt.
 *
 * Die eigentliche Bedienung steckt in assets/builder.js; hier steht nur das
 * Gerüst samt Ausgangsdaten. Gespeichert wird ein JSON-Feld, aus dem der
 * Server anschließend das E-Mail-HTML erzeugt (lib/Blocks.php).
 */

/**
 * @param array  $blocks    Bausteine (Ergebnis von Blocks::parse)
 * @param string $mode      'campaign' oder 'template'
 * @param string $fieldName Name des versteckten Formularfelds
 */
function builder_ui(array $blocks, string $mode = 'campaign', string $fieldName = 'blocks_json'): void
{
    $typen = Blocks::TYPES;
    if ($mode !== 'template') {
        // Inhaltsplatzhalter, Kopfzeile und Footer gehören zur Vorlage
        unset($typen['content'], $typen['kopf'], $typen['fuss']);
    }
    $icons = [
        'heading' => 'H', 'text' => '¶', 'image' => '▣', 'button' => '⬛',
        'divider' => '—', 'spacer' => '↕', 'columns' => '▥', 'social' => '⋯',
        'html' => '&lt;/&gt;', 'content' => '⧉', 'kopf' => '▤', 'fuss' => '▨',
    ];
    ?>
    <div class="bk" data-builder data-mode="<?= Util::e($mode) ?>"
         data-upload="upload.php"
         data-autosave="1"
         data-csrf="<?= Util::e(Util::csrfToken()) ?>">

        <!-- Bausteine zum Hineinziehen -->
        <aside class="bk-palette">
            <h3>Bausteine</h3>
            <p class="bk-hint">Ziehen Sie einen Baustein nach rechts – oder klicken Sie ihn an,
                dann wird er unten angehängt.</p>
            <?php foreach ($typen as $key => $label): ?>
                <button type="button" class="bk-chip" draggable="true" data-add="<?= Util::e($key) ?>">
                    <span class="bk-chip-icon" aria-hidden="true"><?= $icons[$key] ?? '•' ?></span>
                    <?= Util::e($label) ?>
                </button>
            <?php endforeach; ?>

            <h3 style="margin-top:22px;">Platzhalter</h3>
            <p class="bk-hint">Setzt den Platzhalter an der Schreibmarke ein.</p>
            <select class="bk-select" data-placeholder-picker>
                <option value="">— einsetzen —</option>
                <?php foreach (Renderer::placeholderHelp() as $code => $bedeutung): ?>
                    <?php if ($mode === 'campaign' && $code === '{{inhalt}}') { continue; } ?>
                    <option value="<?= Util::e($code) ?>"><?= Util::e($code) ?> – <?= Util::e(Util::shorten($bedeutung, 40)) ?></option>
                <?php endforeach; ?>
            </select>
        </aside>

        <!-- Arbeitsfläche -->
        <div class="bk-stage">
            <div class="bk-stage-bar">
                <span class="bk-stage-title"><?= $mode === 'template' ? 'Vorlage' : 'Ausgabe' ?> zusammenstellen</span>
                <span class="bk-autosave" data-autosave-status></span>
                <span class="bk-stage-info" data-count></span>
            </div>
            <div class="bk-canvas" data-canvas></div>
            <p class="bk-hint bk-canvas-hint">Tipp: Bausteine lassen sich am Griff <span aria-hidden="true">⠿</span>
                verschieben. Auf dem Handy nutzen Sie die Pfeiltasten am Baustein.</p>
        </div>

        <!-- Einstellungen -->
        <aside class="bk-inspector" data-inspector></aside>

        <textarea name="<?= Util::e($fieldName) ?>" data-blocks-field hidden><?php
            echo Util::e((string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        ?></textarea>
    </div>

    <script>
        window.NL_FONTS = <?= json_encode(Blocks::fonts(), JSON_UNESCAPED_UNICODE) ?>;
        window.NL_BLOCK_LABELS = <?= json_encode(Blocks::TYPES, JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <?php
}
