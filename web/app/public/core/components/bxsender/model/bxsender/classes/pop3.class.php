<?php
if (!class_exists('POP3')) {
    require_once MODX_CORE_PATH . 'model/modx/mail/phpmailer/class.pop3.php';
}

class bxPOP3 extends POP3
{
    /* @var modX $modx */
    public $modx;
    /* @var modCacheManager $cacheManager */
    public $cacheManager;

    /* @var string $msg */
    public $msg = null;
    
    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->cacheManager = $this->modx->getCacheManager();
    }

    /**
     * @param string $host
     * @param bool $port
     * @param bool $timeout
     * @param string $username
     * @param string $password
     * @param bool $ssl
     * @param int $debug_level
     * @return bool
     */
    public function authorise($host, $port = false, $timeout = false, $username = '', $password = '', $ssl = true, $debug_level = 0)
    {
        if ($ssl) {
            $host = 'ssl://' . $host;
            if (!$port) {
                $port = 995;
            }
        }
        if (!$port) {
            $port = 110;
        }
        
        return parent::authorise($host, $port, $timeout, $username, $password, $debug_level);
    }

    /**
     * Вернет список сообщений
     * @return array|null
     */
    public function getListUIDL()
    {
        $messages = null;
        if ($response = $this->request('UIDL', true)) {
            $messages = [];
            $line = strtok($response, "\n");
            while ($line) {
                list($no, $uuid) = explode(' ', trim($line));
                $messages[(int)$no] = $uuid;
                $line = strtok("\n");
            }
        }
        return $messages;
    }



    /**
     * Вернет сообщение
     * @param $index_msg
     * @return bool|string
     */
    public function read($index_msg)
    {
        try {
            $this->msg = $this->request("RETR $index_msg", true);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Сохранение сообщения в файл
     * @param string $path
     *
     * @return bool
     */
    public function saveToFile($filePath, $index_msg, $uuid)
    {
        if (!file_exists($filePath)) {
            $response = $this->read($index_msg);
            if ($response === true) {
                return $this->cacheManager->writeFile($filePath, $this->msg);
            } else {
                return false;
            }
        }
        return true;
    }


    /**
     * read a response
     *
     * @param  bool $multiline response has multiple lines and should be read until "<nl>.<nl>"
     *
     * @throws \RuntimeException
     * @return string response
     */
    protected function readResponse($multiline = false)
    {
        $result = null;
        try {
            $result = fgets($this->pop_conn);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        if (!is_string($result)) {
            throw new RuntimeException('read failed - connection closed?' . $result);
        }

        $result = trim($result);
        if (strpos($result, ' ')) {
            list($status, $message) = explode(' ', $result, 2);
        } else {
            $status = $result;
            $message = '';
        }
        if ($status != '+OK') {
            throw new \RuntimeException($result);
        }
        if ($multiline) {
            $message = '';
            $line = fgets($this->pop_conn);
            while ($line && rtrim($line, "\r\n") != '.') {
                if ($line[0] == '.') {
                    $line = substr($line, 1);
                }
                $message .= $line;
                $line = fgets($this->pop_conn);
            };
        }
        return $message;
    }

    /**
     * Отключаем стандартну функцию по причини отключения после авторизации
     */
    public function disconnect()
    {
    }

    /**
     * Disconnect from the POP3 server.
     * @access public
     */
    public function logout()
    {
        parent::disconnect();
    }

    /**
     * @param $request
     * @param bool $multiline
     * @return string
     */
    protected function request($request, $multiline = false)
    {
        $this->sendString($request . self::CRLF);
        return $this->readResponse($multiline);
    }
}