<?php

class CheckController extends PluginController
{
    public function all_action()
    {
        $this->results = array();

        if (mb_internal_encoding() !== "UTF-8") {
            $this->results[] = array(
                'type' => "error",
                'message' => _("Das interne Encoding steht nicht auf UTF-8. Überprüfen Sie php_value mbstring.internal_encoding utf-8 in der Apache-Konfiguration.")
            );
        }
    }
}