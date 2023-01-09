<?php

class bxMailSender
{
    const STATUS_PAUSED = 'paused';
    const RETRY_ATTEMPTS_LIMIT = 3;
    const RETRY_INTERVAL = 120; // seconds

    /* @var modX $modx */
    public $modx;

    /* @var array|null $settings */
    protected $settings = null;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->loadSettings();
    }

    /**
     * @return array
     */
    private function loadSettings()
    {
        if (is_null($this->settings)) {
            $this->settings = array(
                'from' => '',
                'from_name' => '',
                'reply_to' => '',
                'transport' => 'system',
                'method' => 'ajax',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '',
                'prefix' => '',
                'auth' => true,
                'frequency_emails' => '',
                'frequency_interval' => '',
                'message_verification' => '',
                'mailing_log' => array(),
            );

            $keys = array();
            foreach ($this->settings as $k => $value) {
                $keys[] = 'bxsender_mailsender_' . $k;
            }

            //bxsender_mailsender_
            /* @var modSystemSetting $object */
            $q = $this->modx->newQuery('modSystemSetting');
            $q->select('key,value');
            $q->where(array(
                'key:IN' => $keys
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = $row['key'];
                    $value = $row['value'];
                    $key = str_ireplace('bxsender_mailsender_', '', $key);
                    if ($key == 'mailing_log') {
                        $value = !empty($value) ? $this->modx->fromJSON($value) : $this->createMailerLog();
                    }
                    $this->settings[$key] = $value;
                }
            }
        }
        return $this->settings;
    }


    /**
     * @return array|null
     */
    public function toArray()
    {
        return $this->settings;
    }

    /**
     * @param array $data
     */
    public function fromArray($data = array())
    {
        foreach ($this->settings as $key => $setting) {
            if (isset($data[$key])) {
                $this->settings[$key] = $data[$key];
            }
        }
    }

    /**
     * Сохраненеи настроек
     */
    public function save()
    {
        foreach ($this->settings as $key => $value) {
            $key_option = 'bxsender_mailsender_' . $key;

            if ($key == 'mailing_log') {
                // Запрещаем обновлять mailing_log через метод save
                continue;
            }
            if ($SystemSetting = $this->modx->getObject('modSystemSetting', $key_option)) {
                $value = is_null($value) ? '' : $value;
                $SystemSetting->set('value', $value);
                $SystemSetting->set('editedon', time());
                $SystemSetting->save();
            }
        }

        // Сброс кэша
        $this->modx->getCacheManager();
        $this->modx->cacheManager->refresh(array(
            'system_settings' => array()
        ));
    }

    /**
     * @return bool
     */
    public function isSMTP()
    {
        return $this->get('transport') == 'smtp' ? true : false;
    }


    /**
     * Установка траспортировщика почтовых сообщений
     * @param modPHPMailer $mail
     */
    public function transporter($mail)
    {

        $mail->setHTML(true);

        switch ($this->get('transport')) {
            case 'smtp':

                $mail->set(modMail::MAIL_FROM, $this->get('from'));
                $mail->set(modMail::MAIL_FROM_NAME, $this->get('from_name'));
                $reply_to = $this->get('reply_to');
                $reply_to = !empty($reply_to) ? $reply_to : $this->get('from');
                $mail->address('reply-to', $reply_to);


                $params = $this->get(array('host', 'port', 'prefix', 'username', 'password'));
                $params = array_map('trim', $params);

                $host = $params['host'];
                $port = $params['port'];
                $prefix = $params['prefix'];
                $username = $params['username'];
                $password = $params['password'];


                $mail->set(modMail::MAIL_ENGINE, 'smtp'); // Протокол отправки
                $mail->set(modMail::MAIL_SMTP_AUTH, true); // Авторизация через SMTP включена
                $mail->set(modMail::MAIL_SMTP_HOSTS, $host); // Сервер подключения
                $mail->set(modMail::MAIL_SMTP_KEEPALIVE, true); // Удерживать соединение
                $mail->set(modMail::MAIL_SMTP_PORT, $port); // Порт

                $prefix = strtolower($prefix);
                if ($prefix == 'ssl' or $prefix != 'tls') {
                    $mail->set(modMail::MAIL_SMTP_PREFIX, $prefix); // SSL или TSL
                }

                $mail->set(modMail::MAIL_SMTP_SINGLE_TO, false); // Посылать по одному
                $mail->set(modMail::MAIL_SMTP_TIMEOUT, 10); // Таймаут
                $mail->set(modMail::MAIL_SMTP_USER, $username); // Пользователь
                $mail->set(modMail::MAIL_SMTP_PASS, $password); // Пароль

                break;
            case 'system':
                $mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
                $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
                $mail->set(modMail::MAIL_READ_TO, $this->modx->getOption('emailsender'));
                break;
            case 'server':
                $mail->set(modMail::MAIL_FROM, $this->get('from'));
                $mail->set(modMail::MAIL_FROM_NAME, $this->get('from_name'));
                $mail->set(modMail::MAIL_READ_TO, $this->get('reply_to'));
                break;
            default:
                break;
        }
    }

    /**
     * @param array $mailingLog
     * @return array|bool
     */
    private function updateMailerLog($mailingLog = array())
    {
        global $modx;
        $c = $modx->newQuery('modSystemSetting');
        $c->command('UPDATE');

        $mailingLog = is_array($mailingLog) ? $this->modx->toJSON($mailingLog) : $mailingLog;
        $c->query['set']['value'] = array(
            'value' => $mailingLog,
            'type' => true,
        );

        $c->where(array(
            'key' => 'bxsender_mailsender_mailing_log',
        ));
        $c->prepare();
        if (!$c->stmt->execute()) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), 1), '', __METHOD__, __FILE__, __LINE__);
            return false;
        } else {
            $this->set('mailing_log', $mailingLog);
        }
        return $mailingLog;
    }


    public function set($field, $value)
    {
        $this->settings[$field] = $value;
    }


    /**
     * @param $field
     * @return array|null|string
     */
    public function get($field)
    {
        $value = null;
        if (is_array($field)) {
            foreach ($field as $k) {
                if (isset($this->settings[$k])) {
                    $v = $this->settings[$k];
                    if ($k == 'mailing_log') {
                        $v = $this->modx->fromJSON($v);
                    }
                    $value[$k] = $v;
                }
            }
        } else {
            if (isset($this->settings[$field])) {
                $value = $this->settings[$field];
                if ($field == 'mailing_log') {
                    if (!empty($value) and !is_array($value)) {
                        $value = $this->modx->fromJSON($value);
                    }
                }
            }
        }
        return $value;
    }


    /**
     * @param bool|array $mailer_log
     * @return bool|false|int|mixed
     */
    public function getMailerLog($mailer_log = false)
    {
        if ($mailer_log) {
            return $mailer_log;
        } else {
            $mailer_log = $this->get('mailing_log');
            if (empty($mailer_log) and !is_array($mailer_log)) {
                $mailer_log = $this->createMailerLog();
            } else {
                foreach ($mailer_log as $key => $value) {
                    switch ($key) {
                        case 'started':
                            $mailer_log[$key] = (int)$value;

                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $mailer_log;
    }

    /**
     * @return array
     */
    public function resetMailerLog()
    {
        return $this->createMailerLog();
    }


    /**
     * @return array
     */
    private function createMailerLog()
    {
        $mailingLog = array(
            'sent' => null,
            'started' => time(),
            'status' => null,
            'retry_attempt' => null,
            'retry_at' => null,
            'error' => null
        );
        $this->updateMailerLog($mailingLog);
        return $mailingLog;
    }

    /**
     * Process error, increase retry_attempt and block sending if it goes above RETRY_INTERVAL
     *
     * @param string $operation
     * @param string $error_message
     * @param string $error_code
     * @param bool $pause_sending
     *
     * @throws \Exception
     */
    public function processError($operation, $error_message, $error_code = null, $pause_sending = false)
    {
        $mailer_log = $this->getMailerLog();
        $mailer_log['retry_attempt']++;
        $mailer_log['retry_at'] = time() + self::RETRY_INTERVAL;

        #$this->modx->log(modX::LOG_LEVEL_ERROR, "Error: {$error_message}", '', __METHOD__, __FILE__, __LINE__);
        $mailer_log = $this->setError($mailer_log, $operation, $error_message, $error_code);
        $this->updateMailerLog($mailer_log);

        if ($pause_sending) {
            $this->pauseSending($mailer_log);
        }
        $this->enforceExecutionRequirements();
    }

    /**
     * @param $mailer_log
     * @return null
     */
    private function pauseSending($mailer_log)
    {
        $mailer_log['status'] = self::STATUS_PAUSED;
        $mailer_log['retry_attempt'] = null;
        $mailer_log['retry_at'] = null;
        return $this->updateMailerLog($mailer_log);
    }

    /**
     * @param $mailer_log
     * @param $operation
     * @param $error_message
     * @param null $error_code
     * @return mixed
     */
    private function setError($mailer_log, $operation, $error_message, $error_code = null)
    {
        $mailer_log['error'] = array(
            'operation' => $operation,
            'error_message' => $error_message
        );
        if ($error_code) {
            $mailer_log['error']['error_code'] = $error_code;
        }
        return $mailer_log;
    }

    private function getError($mailer_log = false)
    {
        $mailer_log = $this->getMailerLog($mailer_log);
        return isset($mailer_log['error']) ? $mailer_log['error'] : null;
    }


    /**
     * @throws ExceptionSending
     */
    public function enforceExecutionRequirements()
    {
        $mailer_log = $this->getMailerLog();
        if ($mailer_log['retry_attempt'] === self::RETRY_ATTEMPTS_LIMIT) {
            $mailer_log = self::pauseSending($mailer_log);
        }

        if ($this->isSendingPaused()) {
            throw new ExceptionSending('Sending has been paused.');
        }

        if (!is_null($mailer_log['retry_at'])) {
            if (time() <= $mailer_log['retry_at']) {
                throw new ExceptionSending('Sending is waiting to be retried.');
            } else {
                $mailer_log['retry_at'] = null;
                $this->updateMailerLog($mailer_log);
            }
        }

        // ensure that sending frequency has not been reached
        if ($this->isSendingLimitReached($mailer_log)) {
            throw new ExceptionSending('Sending frequency limit has been reached');
        }
    }

    /**
     * @return bool
     */
    private function isSendingPaused($mailer_log = false)
    {
        $mailer_log = $this->getMailerLog($mailer_log);
        return $mailer_log['status'] === self::STATUS_PAUSED;
    }


    /**
     * @return false|int|mixed
     */
    private function getMailerConfig()
    {
        // Временное решение
        $config = $this->get(array(
            'frequency_emails',
            'frequency_interval'
        ));
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'frequency_interval':
                    $config[$key] = $value * 60;
                    break;
                default:
                    break;
            }
        }
        return $config;
    }


    /**
     * Проверка интервалов времени для отправкии сообщений
     * @return bool
     */
    private function isSendingLimitReached($mailer_log = false)
    {
        $mailer_config = $this->getMailerConfig();
        $mailer_log = $this->getMailerLog($mailer_log);
        $elapsed_time = time() - (int)$mailer_log['started'];
        if ($mailer_log['sent'] >= $mailer_config['frequency_emails']) {
            if ($elapsed_time <= $mailer_config['frequency_interval']) {
                return true;
            }
            $this->resetMailerLog();
        }
        return false;
    }


    /**
     * Увеличиваем счетчик отправленных сообщений
     * @return array|bool
     */
    public function incrementSentCount()
    {
        $mailer_log = $this->getMailerLog();

        // Не увеличивать счетчик, если достигнут лимит отправки
        if ($this->isSendingLimitReached($mailer_log)) return true;

        // очистить счетчик предыдущих попыток, ошибки и т. д.
        if ($mailer_log['error']) {
            $mailer_log = $this->clearSendingErrorLog($mailer_log);
        }

        $sent = (int)$mailer_log['sent'];
        $sent++;
        $mailer_log['sent'] = $sent;
        return $this->updateMailerLog($mailer_log);
    }


    /**
     * @param $mailer_log
     * @return null
     */
    private function clearSendingErrorLog($mailer_log)
    {
        $mailer_log['retry_attempt'] = null;
        $mailer_log['retry_at'] = null;
        $mailer_log['error'] = null;
        return $this->updateMailerLog($mailer_log);
    }


}