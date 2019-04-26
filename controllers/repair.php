<?php

class RepairController extends PluginController
{
    public function tmp_action()
    {
        if (!$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException();
        }
        if ($GLOBALS['TMP_PATH']) {

            foreach ((array) @scandir($GLOBALS['TMP_PATH']) as $file) {
                $this->deleteFile($GLOBALS['TMP_PATH'] . "/" . $file);
            }
            PageLayout::postSuccess(_("TMP-Ordner wurde geleert."));
        } else {
            PageLayout::postError(_("TMP-Ordner wurde nicht gefunden."));
        }
        $this->redirect(PluginEngine::getURL($this->plugin, array(), "check/all"));
    }

    public function check_for_links_action() {
        if (!$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException();
        }
        if ($GLOBALS['TMP_PATH']) {
            foreach ((array) @scandir($GLOBALS['TMP_PATH']) as $file) {
                $this->checkFile($GLOBALS['TMP_PATH'] . "/" . $file);
            }
        }
        $this->render_nothing();
    }

    protected function checkFile($path) {
        if (is_link($path)) {
            echo $path."<br>\n";
        }
        if (!in_array($path, array(".", "..")) && !is_link($path)) {
            if (is_dir($path)) {
                foreach ((array) @scandir($path) as $file) {
                    if (!in_array($file, array(".", ".."))) {
                        $this->deleteFile($path."/".$file);
                    }
                }
            }
        }
    }

    protected function deleteFile($path) {
        if (!in_array($path, array(".", "..")) && !is_link($path)) {
            if (is_dir($path)) {
                foreach ((array) @scandir($path) as $file) {
                    if (!in_array($file, array(".", ".."))) {
                        $this->deleteFile($path."/".$file);
                    }
                }
                @rmdir($path."/".$file);
            } else {
                @unlink($path);
            }
        }
    }

    public function cache_action()
    {
        PageLayout::postMessage(MessageBox::success(_("Cache wurde geleert.")));
        SimpleORMap::expireTableScheme();
        $this->redirect(PluginEngine::getURL($this->plugin, array(), "check/all"));
    }
}