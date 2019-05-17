<?php

class ReporterController extends PluginController
{
    public function json_action($secret)
    {
        if ($secret !== md5(Config::get()->STUDIP_INSTALLATION_ID."Reporter")) {
            throw new AccessDeniedException();
        }
        $output = array();
        $output['installation_name'] = Config::get()->UNI_NAME_CLEAN;
        $output['load'] = sys_getloadavg();
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

        $this->render_json($output);
    }
}