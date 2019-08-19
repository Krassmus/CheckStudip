<?php

class CheckController extends PluginController
{
    public function all_action()
    {
        Navigation::activateItem("/admin/locations/checkstudip");
        if (!$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException();
        }
        $this->results = array();

        if (mb_internal_encoding() !== "UTF-8") {
            $this->results[] = array(
                'type' => "error",
                'message' => _("Das interne Encoding steht nicht auf UTF-8. Überprüfen Sie php_value mbstring.internal_encoding utf-8 in der Apache-Konfiguration.")
            );
        }

        $space = disk_free_space($GLOBALS['TMP_PATH']);
        $min = Config::get()->DISK_FREE_SPACE_ALERT;
        if ($space > 1024 * 1024 * 1024 * 1024) {
            $this->fds_number = round($space / (1024 * 1024 * 1024 * 1024), 2);
            $this->fds_unit = "TB";
        } elseif ($space > 1024 * 1024) {
            $this->fds_number = round($space / (1024 * 1024 * 1024), 2);
            $this->fds_unit = "GB";
        } elseif ($space > 1024 * 1024) {
            $this->fds_number = round($space / (1024 * 1024), 2);
            $this->fds_unit = "MB";
        } else {
            $this->fds_number = round($space / (1024), 2);
            $this->fds_unit = "KB";
        }
        if ($min && $space < $min) {

            $this->results[] = array(
                'type' => "info",
                'message' => sprintf(_("Nur noch %s %s an Speicher im Temp-Ordner frei."), $this->fds_number, $this->fds_unit)
            );
        }

        $writable_folders = array(
            "data/upload_doc", "data/assets_cache", "public/plugins_packages", "public/pictures",
            "public/pictures/course", "public/pictures/user", "public/pictures/institute",
            "public/pictures/loginbackgrounds", "public/pictures/blubberstream"
        );
        foreach ($writable_folders as $folder) {
            if (!is_writable($GLOBALS['STUDIP_BASE_PATH']."/".$folder)) {
                $this->results[] = array(
                    'type' => "error",
                    'message' => sprintf(_("Ordner %s ist nicht schreibbar."), $folder)
                );
            }
        }

        $success = file_get_contents(
            "https://develop.studip.de",
            FALSE ,
            NULL,
            0 ,
            10
        );
        if ($success === false) {
            $success = file_get_contents(
                "https://www.google.de",
                FALSE ,
                NULL,
                0 ,
                10
            );
            if ($success === false) {
                $this->results[] = array(
                    'type' => "info",
                    'message' => _("Der Server hat keinen Zugriff auf das Internet.")
                );
            }
        }


        $output = array();
        $output['installation_name'] = Config::get()->UNI_NAME_CLEAN;
        $output['load'] = sys_getloadavg();
        $output['load'] = $output['load'][1]; //Load der letzten 5 Minuten
        //$output['cpu_cores'] = (int) trim(shell_exec("sysctl -n hw.ncpu"));
        $output['free_space_data'] = disk_free_space($GLOBALS['UPLOAD_PATH']);
        $output['free_space_tmp'] = disk_free_space($GLOBALS['TMP_PATH']);

        $start = microtime(true);
        $statement = DBManager::get()->prepare("
            SELECT COUNT(*)
            FROM seminar_user
        ");
        $statement->execute();
        $statement->fetch();
        $end = microtime(true);

        $output['seminar_user_request_time'] = ($end - $start);

        $output['users_online'] = get_users_online_count();

        $this->report = $output;

    }
}