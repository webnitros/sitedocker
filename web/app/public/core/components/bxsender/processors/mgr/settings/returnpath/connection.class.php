<?php

class bxReturnPathConnectionProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $this->modx->lexicon->load('bxsender:manager');


        if (!$ReturnPath = $bxSender->loadReturnPath()) {
            return 'Error load class ReturnPath';
        }

        $params = $ReturnPath->get(array('host', 'ssl', 'username', 'password', 'port', 'timeout'));
        list($host, $ssl, $username, $password, $port, $timeout) = array_values(array_map('trim', $params));
        $debug_level = $this->getProperty('debug_level', false);

        $response = true;

        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        try {

            if (!$bxPOP3 = $bxSender->loadPOP3()) {
                $response = 'Error load class bxPOP3';
            } else {
                if (!$bxPOP3->authorise($host, $port, $timeout, $username, $password, $ssl, $debug_level)) {
                    $response = $this->modx->lexicon('bxsender_settings_returnpath_connect_error');
                }
                $bxPOP3->logout();
            }
        } catch (Exception $e) {
            $response = $e->getMessage();
        } catch (RuntimeException $e) {
            $response = $e->getMessage();
        }

        if ($response !== true) {
            return $this->failure('Error: ' . $response);
        }

        return $this->success($this->modx->lexicon('bxsender_settings_returnpath_connect_success'));
    }
}

return 'bxReturnPathConnectionProcessor';