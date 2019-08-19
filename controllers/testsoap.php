<?php

class TestsoapController extends PluginController
{
    public function compose_action()
    {
        Navigation::activateItem("/admin/locations/checkstudip");
        if (!$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException();
        }
    }

    public function soapit_action()
    {
        Navigation::activateItem("/admin/locations/checkstudip");
        if (!$GLOBALS['perm']->have_perm("root") || !Request::isPost()) {
            throw new AccessDeniedException();
        }

        $client = new SoapClient(Request::get("wsdl"), array(
            'connection_timeout' => 60 * 60,
            'trace' => 1,
            'exceptions' => 0,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        ));
        if (Request::get("username")) {
            $file = strtolower(substr(Request::get("wsdl"), strrpos(Request::get("wsdl"), "/") + 1));
            $soapHeaders = new SoapHeader($file, 'Header', array(
                'Login' => $evasys_user,
                'Password' => $evasys_password
            ));
            $client->__setSoapHeaders($soapHeaders);
        }
        if (is_soap_fault($client)) {
            PageLayout::postError("SOAP-Error: " . $client);
            $this->redirect(PluginEngine::getURL($this->plugin, $_POST, "testsoap/compose"));
            return;
        }

        $arguments = Request::get("json")
            ? json_decode(Request::get("json"), true)
            : null;

        if (Request::get("json") && !$arguments) {
            PageLayout::postError("Parameter sind nicht im JSON-Format.");
            $this->redirect(PluginEngine::getURL($this->plugin, $_POST, "testsoap/compose"));
            return;
        }

        $output = $client->__soapCall(
            Request::get("method"),
            $arguments
            //$options = null,
            //$input_headers = null,
            //$output_headers = null
        );

        ob_start();
        var_dump($output);
        $str = ob_get_clean();

        $params = array_merge($_POST, array('output' => $str));

        //var_dump($output);
        //$this->render_nothing();
        $this->redirect(PluginEngine::getURL($this->plugin, $params, "testsoap/compose"));

    }
}