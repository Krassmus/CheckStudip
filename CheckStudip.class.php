<?php

class CheckStudip extends StudIPPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();
        if ($GLOBALS['perm']->have_perm("root")) {
            $nav = new Navigation(
                _("Stud.IP überprüfen"),
                PluginEngine::getURL($this, array(), "check/all")
            );
            Navigation::addItem("/admin/locations/checkstudip", $nav);

            $space = disk_free_space($GLOBALS['TMP_PATH']);
            $min = Config::get()->DISK_FREE_SPACE_ALERT;
            if ($min && $space < $min) {
                if ($space > 1024 * 1024 * 1024 * 1024) {
                    $number = round($space / (1024 * 1024 * 1024 * 1024), 2);
                    $unit = "TB";
                } elseif ($space > 1024 * 1024) {
                    $number = round($space / (1024 * 1024 * 1024), 2);
                    $unit = "GB";
                } elseif ($space > 1024 * 1024) {
                    $number = round($space / (1024 * 1024), 2);
                    $unit = "MB";
                } else {
                    $number = round($space / (1024), 2);
                    $unit = "KB";
                }
                PageLayout::postInfo(sprintf(_("Nur noch %s %s an Speicher im Temp-Ordner frei."), $number, $unit));
            }
        }
    }
}