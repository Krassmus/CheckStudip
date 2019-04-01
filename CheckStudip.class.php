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
        }
    }
}