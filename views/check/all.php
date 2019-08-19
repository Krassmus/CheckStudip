<? if (count($results)) : ?>
    <? foreach ($results as $result) {
        $type = $result['type'];
        echo MessageBox::$type($result['message']);
    } ?>
<? else : ?>
    <?= MessageBox::success(_("Ihr Stud.IP sieht gut aus! Es konnten keine Probleme festgestellt werden.")) ?>
<? endif ?>

<table class="default hover">
    <thead>
        <tr>
            <th><?= _("Indikator") ?></th>
            <th><?= _("Wert") ?></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($report as $index => $value) : ?>
        <tr>
            <td><?= htmlReady($index) ?></td>
            <td><?= htmlReady($value) ?></td>
        </tr>
        <? endforeach ?>
    </tbody>
</table>


<?

$actions = new ActionsWidget();

$actions->addLink(
    sprintf(_("Cache leeren"), $fds_number, $fds_unit),
    PluginEngine::getURL($plugin, array(), "repair/cache"),
    Icon::create("trash", "clickable")
);
$actions->addLink(
    sprintf(_("TMP-Ordner leeren (%s %s frei)"), $fds_number, $fds_unit),
    PluginEngine::getURL($plugin, array(), "repair/tmp"),
    Icon::create("trash", "clickable"),
    array('onClick' => "return window.confirm('"._("Wirklich leeren?")."');")
);
$actions->addLink(
    _("SOAP-Abfrage testen"),
    PluginEngine::getURL($plugin, array(), "testsoap/compose"),
    Icon::create($plugin->getPluginURL()."/assets/soap.svg", "clickable"),
    array('data-dialog' => 1)
);
$actions->addLink(
    _("JSON-Daten exportieren"),
    PluginEngine::getURL($plugin, array(), "reporter/json/".md5(Config::get()->STUDIP_INSTALLATION_ID."Reporter")),
    Icon::create("export", "clickable")
);
Sidebar::Get()->addWidget($actions);