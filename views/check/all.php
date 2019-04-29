<? if (count($results)) : ?>
    <? foreach ($results as $result) {
        $type = $result['type'];
        echo MessageBox::$type($result['message']);
    } ?>
<? else : ?>
    <?= MessageBox::success(_("Ihr Stud.IP sieht gut aus! Es konnten keine Probleme festgestellt werden.")) ?>
<? endif ?>


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
/*$actions->addLink(
    sprintf(_("TMP-Ordner nach SymLinks prüfen"), $fds_number, $fds_unit),
    PluginEngine::getURL($plugin, array(), "repair/check_for_links"),
    Icon::create("question-circle", "clickable")
);*/
Sidebar::Get()->addWidget($actions);