<?php
/**
 * Get an Item
 */
class bxUnDeliverableGettingProcessor extends modProcessor
{

    /* @var bxReturnPath $returnPath */
    public $returnPath;

    /* @var string $path */
    public $path;


    public function initialize()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        if (!$this->returnPath = $bxSender->loadReturnPath()) {
            return 'Не удалось получить параметры';
        }
        return true;

    }

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {

        $total_message = 0;
        $params = $this->returnPath->get(array('host', 'ssl', 'username', 'password', 'port', 'timeout'));

        list($host, $ssl, $username, $password, $port, $timeout) = array_values(array_map('trim', $params));


        $debug_level = $this->getProperty('debug_level', false);

        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $response = true;
        try {

            if (!$bxPOP3 = $bxSender->loadPOP3()) {
                return $this->failure('Error load class bxPOP3');
            }

            if ($bxPOP3->authorise($host, $port, $timeout, $username, $password, $ssl, $debug_level)) {
                $list = $bxPOP3->getListUIDL();
                if (is_array($list) and count($list) > 0) {
                    foreach ($list as $index_msg => $UUID) {
                        if (!$this->returnPath->findToken($UUID)) {
                            // Сохраняем сообщение во временной папке
                            $savePath = $this->returnPath->getName($UUID);
                            $isSave = false;
                            if (!$bxPOP3->saveToFile($savePath, $index_msg, $UUID)) {
                                $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось сохранить: " . $savePath, '', __METHOD__, __FILE__, __LINE__);
                            } else {
                                $isSave = true;
                            }
                            if ($isSave) {
                                $total_message++;
                            }
                        }
                    }
                }
            } else {
                $error = $bxPOP3->getErrors();
                if (count($error) > 0) {
                    $response = $error[0]['error'];
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error connect POP3 ' . print_r($bxPOP3->getErrors(), 1), '', __METHOD__, __FILE__, __LINE__);
                }
            }

            $bxPOP3->logout();
        } catch (Exception $e) {
            $response = $e->getMessage();
        } catch (RuntimeException $e) {
            $response = $e->getMessage();
        }

        if ($response !== true) {
            $message = "Error getting, message: " . $response . ". host: {$host}, host: {$username}, path: {$this->path}";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($message);
        }

        return $this->success('', array(
            'total_message' => $total_message,
        ));
    }

}

return 'bxUnDeliverableGettingProcessor';