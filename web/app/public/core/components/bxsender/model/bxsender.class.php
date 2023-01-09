<?php
/**
 * The base class for Subscribe.
 */

if (!class_exists('ExceptionSending')) {
    class ExceptionSending extends Exception
    {
        // Переопределим исключение так, что параметр message станет обязательным
        public function __construct($message, $code = 0, Exception $previous = null)
        {
            // некоторый код

            // убедитесь, что все передаваемые параметры верны
            parent::__construct($message, $code, $previous);
        }
    }
}

class bxSender
{
    /* @var modX $modx */
    public $modx;

    /** @var bxMailingHandler $mailing */
    public $mailing;
    public $initialized = array();

    /* @var pdoFetch|boolean|null $pdoFetch */
    public $pdoFetch;

    /* @var bxPOP3 $bxPOP3 */
    protected $bxPOP3 = null;
    public $timer;

    /* @var ParseCsv\Csv|null|boolean $ParseCsv */
    protected $ParseCsv = null;

    protected $autoload = false;

    /* @var bxMailingHandler|null $handlerMailing */
    protected $handlerMailing = null;

    /* @var bxQueryHandler|null $handlerQuery */
    protected $handlerQuery = null;

    /* @var bxManagerSubscribeHandler|null $managerSubscribe */
    protected $managerSubscribe = null;

    /* @var bxMailSender|null $mailsender */
    protected $mailsender = null;

    /* @var bxReturnPath|null $returnpath */
    protected $returnpath = null;

    /* @var pQuery|null|boolean $pQuery */
    protected $pQuery = null;

    /* @var \Pelago\Emogrifier|null|boolean $emogrifier */
    protected $emogrifier = null;

    /* @var Html2Text\Html2Text|null|boolean $html2text */
    protected $html2text = null;

    public $key_hash = 'queue_hash';


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('bxsender_core_path', $config, $this->modx->getOption('core_path') . 'components/bxsender/');
        $assetsUrl = $this->modx->getOption('bxsender_assets_url', $config, $this->modx->getOption('assets_url') . 'components/bxsender/');
        $connectorUrl = $assetsUrl . 'connector.php';
        $this->config = array_merge(array(
            'package' => 'bxSender',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'connector_url' => $connectorUrl,
            'blockingPath' => MODX_ASSETS_PATH . 'components/bxsender/blocking/',
            'linkTTL' => 1800,
            'openbrowserUrl' => $assetsUrl . 'action/openbrowser/index.php',
            'openUrl' => $assetsUrl . 'action/open/index.php',
            'controllersPath' => $corePath . 'controllers/web/',
            'corePath' => $corePath,
            'customPath' => $corePath . 'custom/',
            'modelPath' => $corePath . 'model/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'processorsPath' => $corePath . 'processors/',
            'captchaPath' => $assetsUrl . 'captcha/image.php',
            'json_response' => false,
        ), $config);
        $this->timer = microtime(true);
        $this->modx->lexicon->load('bxsender:default');
    }

    /**
     * @return bool|null|pdoFetch
     */
    public function loadPdoTools()
    {
        if (is_null($this->pdoFetch)) {
            /* @var pdoFetch $pdoFetch */
            if ($this->modx->loadClass('pdofetch')) {

                $this->pdoFetch = new pdoFetch($this->modx);

                $config = array(
                    'useFenom' => true,
                    'useFenomCache' => false,
                    'useFenomParser' => true,
                    'useFenomMODX' => true,
                    'useFenomPHP' => true,
                );

                /*
                   $elements_path = $this->modx->getOption('bxsender_pdotools_elements_path', $this->config, '	{core_path}elements/',true);
                   $this->pdoFetch->setConfig(array(
                       'elementsPath' => $elements_path,
                   ));
                    $config['elementsPath'] = $elements_path;
               */
                foreach ($config as $key => $v) {
                    $this->pdoFetch->config[$key] = $v;
                }

            } else {
                $this->pdoFetch = false;
            }
        }
        return $this->pdoFetch;
    }


    /**
     * @return bxMailSender|null
     */
    public function loadMailSender()
    {
        if (is_null($this->mailsender)) {
            if (!class_exists('bxMailSender')) {
                $this->modx->loadClass('bxMailSender', MODX_CORE_PATH . 'components/bxsender/model/', true, true);
            }
            $this->mailsender = new bxMailSender($this->modx);
        }
        return $this->mailsender;
    }

    /**
     * @return bxReturnPath|null
     */
    public function loadReturnPath()
    {
        if (is_null($this->returnpath)) {
            if (!class_exists('bxReturnPath')) {
                $this->modx->loadClass('bxReturnPath', MODX_CORE_PATH . 'components/bxsender/model/', true, true);
            }
            $this->returnpath = new bxReturnPath($this->modx);
        }
        return $this->returnpath;
    }


    /**
     * Method loads custom classes from specified directory
     *
     * @var string $dir Directory for load classes
     *
     * @return void
     */
    public function loadCustomClasses($dir)
    {
        $files = scandir($this->config['customPath'] . $dir);
        if (is_array($files) and count($files) > 0) {
            foreach ($files as $file) {
                if (preg_match('/.*?\.class\.php$/i', $file)) {
                    include_once($this->config['customPath'] . $dir . '/' . $file);
                }
            }
        }
    }


    public $controllers = null;


    /**
     * Method loads custom controllers
     *
     * @var string $dir Directory for load controllers
     *
     * @return void
     */
    public function loadController($name)
    {
        if (!class_exists('bxSenderControllerDefault')) {
            require_once $this->config['corePath'] . 'controllers/web/controller.class.php';
        }

        $name = strtolower(trim($name));
        $file = $this->config['controllersPath'] . $name . '/' . $name . '.class.php';
        if (!file_exists($file)) {
            $file = $this->config['controllersPath'] . $name . '.class.php';
        }

        if (file_exists($file)) {
            $class = include_once($file);

            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Wrong controller at ' . $file);
            } /* @var bxSenderControllerDefault $controller */
            else if ($controller = new $class($this, $this->config)) {
                if ($controller instanceof bxSenderControllerDefault && $controller->initialize()) {
                    $this->controllers[strtolower($name)] = $controller;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Could not load controller ' . $file);
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Could not find controller ' . $file);
        }
    }

    /**
     * Loads given action, if exists, and transfers work to it
     * @param $action
     * @param array $scriptProperties
     * @param array $data
     *
     * @return string|array
     */
    public function loadAction($action, $scriptProperties = array(), $data = array())
    {
        if (empty($action)) {
            return 'specify controller name';
        }

        @list($name, $action) = explode('/', strtolower(trim($action)));
        if (!isset($this->controllers[$name])) {
            $this->loadController($name);
        }
        if (isset($this->controllers[$name])) {
            /* @var bxSenderControllerDefault $controller */
            $controller = $this->controllers[$name];
            $controller->setDefault($scriptProperties);
            if (empty($action)) {
                return 'Empty action controller name';
            }
            if (method_exists($controller, $action)) {
                if ($action == 'hook') {
                    $scriptProperties = $data;
                }
                return $controller->$action($scriptProperties);
            }
        }
        return 'Could not load controller "' . $name . '"';
    }


    /**
     * Загрузка класса для получения почты
     * @return bool|bxPOP3
     */
    public function loadPOP3()
    {
        if (is_null($this->bxPOP3)) {
            if (!class_exists('bxPOP3')) {
                require_once $this->config['corePath'] . 'model/bxsender/classes/pop3.class.php';
            }
            if (class_exists('bxPOP3')) {
                $this->bxPOP3 = new bxPOP3($this->modx);
            } else {
                $this->bxPOP3 = false;
            }
        }
        return $this->bxPOP3;
    }

    /**
     * Load Vendor
     */
    public function loadAutoLoad()
    {
        if (!$this->autoload) {
            require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
            $this->autoload = true;
        }
    }

    /**
     * Загрузка класса для парсинга CSV
     * @return bool|null|\ParseCsv\Csv
     */
    public function loadClassCSV()
    {
        if (is_null($this->ParseCsv)) {
            $this->loadAutoLoad();
            if (class_exists('ParseCsv\Csv')) {
                $this->ParseCsv = new ParseCsv\Csv();
            } else {
                $this->ParseCsv = false;
            }
        }
        return $this->ParseCsv;
    }


    /**
     * Парсер html
     * @return bool|null|pQuery
     */
    public function loadClassPquery()
    {
        if (is_null($this->pQuery)) {
            $this->loadAutoLoad();
            if (class_exists('pQuery')) {
                $this->pQuery = new pQuery();
            } else {
                $this->pQuery = false;
            }
        }
        return $this->pQuery;
    }

    /**
     * Загрузка класса для парсинга CSV
     * @return bool|null|\Pelago\Emogrifier
     */
    public function loadEmogrifier()
    {
        if (is_null($this->emogrifier)) {
            $this->loadAutoLoad();
            if (class_exists('\Pelago\Emogrifier')) {
                $this->emogrifier = new \Pelago\Emogrifier();
            } else {
                $this->emogrifier = false;
            }
        }
        return $this->emogrifier;
    }

    /**
     * Загрузка класса для парсинга CSV
     * @return bool|null|Soundasleep\Html2Text
     */
    public function loadClassHtml2Text()
    {
        if (is_null($this->html2text)) {
            $this->loadAutoLoad();
            if (class_exists('Soundasleep\Html2Text')) {
                $this->html2text = new Soundasleep\Html2Text();
            } else {
                $this->html2text = false;
            }
        }
        return $this->html2text;
    }

    /**
     * Sends email with activation link
     *
     * @param $email
     * @param array $options
     *
     * @return string|bool
     */
    public function sendEmail($email, array $options = array())
    {
        /** @var modPHPMailer $mail */
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');

        $mail->set(modMail::MAIL_BODY, $this->modx->getOption('email_body', $options, ''));
        $mail->set(modMail::MAIL_FROM, $this->modx->getOption('email_from', $options, $this->modx->getOption('emailsender'), true));
        $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('email_from_name', $options, $this->modx->getOption('site_name'), true));
        $mail->set(modMail::MAIL_SUBJECT, $this->modx->getOption('email_subject', $options, $this->modx->lexicon('bxsender_subscribe_activate_subject'), true));

        $mail->address('to', $email);
        $mail->address('reply-to', $this->modx->getOption('email_from', $options, $this->modx->getOption('emailsender'), true));
        $mail->setHTML(true);

        if (!$response = $mail->send()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[bxSender] Error send " . $mail->mailer->ErrorInfo, '', __METHOD__, __FILE__, __LINE__);
        }
        $mail->reset();
        return $response;
    }

    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return modProcessorResponse|boolean The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/bxsender/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
    }

    /**
     * Shorthand for original modX::invokeEvent() method with some useful additions.
     *
     * @param $eventName
     * @param array $params
     * @param $glue
     *
     * @return array
     */
    public function invokeEvent($eventName, array $params = array(), $glue = '<br/>')
    {
        if (isset($this->modx->event->returnedValues)) {
            $this->modx->event->returnedValues = null;
        }

        $response = $this->modx->invokeEvent($eventName, $params);
        if (is_array($response) && count($response) > 1) {
            foreach ($response as $k => $v) {
                if (empty($v)) {
                    unset($response[$k]);
                }
            }
        }

        $message = is_array($response) ? implode($glue, $response) : trim((string)$response);
        if (isset($this->modx->event->returnedValues) && is_array($this->modx->event->returnedValues)) {
            $params = array_merge($params, $this->modx->event->returnedValues);
        }

        return array(
            'success' => empty($message),
            'message' => $message,
            'data' => $params,
        );
    }

    /**
     * Траспорт для сторонних сервисов
     * @param string $service
     * @param string|array $email_to
     * @param string $email_subject
     * @param string $email_body
     * @param array $variables
     * @param array $options
     * @return bool
     */
    public function transitMessage($service, $email_to, $email_subject, $email_body, $variables = array(), $options = array())
    {
        /* @var sxQueue $queue */
        /* @var bxMailing $Mailing */
        $service = mb_strtolower($service);
        if (!$Mailing = $this->modx->getObject('bxMailing', array('service' => $service))) {


            $service_minishop2 = $this->modx->getOption('bxsender_minishop2_prefix_service', null, 'minishop2');
            if ($service == $service_minishop2) {

                /* @var bxMailing $Mailing */
                $Mailing = $this->modx->newObject('bxMailing');
                $Mailing->set('subject', $service);
                $Mailing->set('service', $service);
                $Mailing->set('message', 'Сообщения формируют статусы minishop2');
                $Mailing->set('active', 1);
                $Mailing->set('utm_source', 'bx_segment_minishop2');
                $Mailing->set('utm_medium', 'bx_medium_minishop2');
                $Mailing->set('utm_campaign', 'bx_campaign_minishop2');
                $Mailing->setStatus('process');
                if (!$Mailing->save()) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось сохранить рассылку для сервиса minishop2", '', __METHOD__, __FILE__, __LINE__);
                    return false;
                }
            }

            if (!$Mailing) {
                if ($service == 'modx') {
                    /* @var bxMailing $object */
                    $response = $this->runProcessor('mgr/sending/mailing/create', array(
                        'subject' => $this->modx->lexicon('bxsender_mailing_name_modx'),
                        'service' => 'modx',
                        'message' => 'none message',
                    ));
                    if ($response->isError()) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, "Error create bxMailing" . print_r($response->getAllErrors(), 1), '', __METHOD__, __FILE__, __LINE__);
                        return false;
                    }
                    $id = $response->response['object']['id'];
                    $Mailing = $this->modx->getObject('bxMailing', $id);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Error could not found bxMailing of service {$service}", '', __METHOD__, __FILE__, __LINE__);
                    return false;
                }
            }
        }

        $default = array(
            'action' => 'query',
            'state' => 'prepare',
        );

        /* @var bxQueue $bxQueue */
        $bxQueue = $this->modx->newObject('bxQueue');


        $emails = array();
        if (is_array($email_to)) {
            foreach ($email_to as $e) {
                $emails[] = $e[0];
            }
            /* $email_to = $email_to[0];
             $email_to = array_filter($email_to);
             $email_to = implode(',', $email_to);*/
        } else {
            $emails[] = $email_to;
        }
        $emails = array_filter($emails);
        if (empty($emails)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось добавить сообщение в очереди, емаил получателя не указан", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        $emails = implode(',', $emails);


        /** @var bxQueue $queue */
        $data = array(
            'mailing_id' => $Mailing->get('id'),
            'service' => $service,
            'email_to' => $emails,
            'email_subject' => $email_subject,
            'email_body' => $email_body,
        );
        $data = array_merge($default, $data);
        $bxQueue->fromArray($data);


        if (is_array($options) and count($options) > 0) {
            /* @var bxOrderLog $object */
            $OrderLog = $this->modx->newObject('bxOrderLog');
            $OrderLog->set('order_id', $options['order_id']);
            $OrderLog->set('status', $options['status']);
            $OrderLog->set('createdon', time());
            $OrderLog->set('from', $options['from']);
            $bxQueue->addMany($OrderLog);
        }

        $bxQueue->save();

        $response = $bxQueue->action('query', false);
        if ($response === true) {
            if ($bxQueue = $this->modx->getObject('bxQueue', $bxQueue->get('id'))) {
                try {
                    $response = $bxQueue->action('send');
                } catch (ExceptionSending $e) {
                    $response = $e->getMessage();
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось получить сообщение в очереди", '', __METHOD__, __FILE__, __LINE__);
                $response = false;
            }
        }
        return $response;
    }


    /**
     * Проверка предела выполнения скрипта
     * @param $timer
     * @throws Exception
     */
    static function enforceExecutionLimit($timer)
    {
        $elapsed_time = microtime(true) - $timer;
        if ($elapsed_time >= 20) {
            throw new ExceptionSending('Maximum execution time has been reached.');
        }
    }

    /**
     * @param string $hash
     * @return array|bool
     */
    public function getHashData($hash = null, $fieldValue = false, $lenStr = 0)
    {
        if (!$hash) {
            $key = $this->key_hash;
            $hash = !empty($_GET[$key]) ? (string)$_GET[$key] : false;
        }

        if ($lenStr) {
            $hash = substr($hash, 0, -$lenStr);
        }

        $result = false;
        if (!empty($hash)) {
            $hash = base64_decode($hash);
            $data = $this->modx->fromJSON($hash);
            if (!empty($data) and is_array($data)) {
                foreach ($data as $field => $value) {
                    switch ($field) {
                        case 'mailing_id':
                        case 'subscriber_id':
                        case 'queue_id':
                            $result[$field] = (int)$value;
                            break;
                        case 'email':
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $result[$field] = trim((string)$value);
                            }
                            break;
                        case 'hash':
                        case 'token':
                            $hash = preg_replace('/[^a-zA-Z0-9]/', '', $value);
                            if (strlen($hash) == 40) {
                                $result[$field] = $hash;
                            }
                            break;
                        default:
                            break;
                    }
                }
            }

            if ($fieldValue) {
                return isset($result[$fieldValue]) ? $result[$fieldValue] : false;
            }
        }
        return $result;
    }


    /**
     * @param string $hash
     * @return array|bool
     */
    public function getToken($hash = null)
    {
        if (!$hash) {
            $key = $this->key_hash;
            $hash = !empty($_GET[$key]) ? (string)$_GET[$key] : false;
        }

        $result = false;
        if (!empty($hash)) {
            $hash = base64_decode($hash);
            $data = $this->modx->fromJSON($hash);
            if (!empty($data) and is_array($data)) {
                foreach ($data as $key => $value) {
                    switch ($key) {
                        case 0:
                            $hash = preg_replace('/[^a-zA-Z0-9]/', '', $value);
                            if (strlen($hash) == 40) {
                                $result['token'] = $hash;
                            }
                            break;
                        case 1:
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $result['email'] = trim((string)$value);
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Load class mailing
     * @return bool|bxMailingHandler|bxMailingInterface|null
     */
    public function loadHandlerMailing()
    {
        if (is_null($this->handlerMailing)) {
            if (!class_exists('bxMailingInterface')) {
                require_once dirname(__FILE__) . '/bxmailinghandler.class.php';
            }

            $class = $this->modx->getOption('bxsender_handler_mailing', null, 'bxMailingHandler');
            if ($class != 'bxMailingHandler') {
                $this->loadCustomClasses('mailing');
            }

            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Mailing handler class "' . $class . '" not found.');
                $class = 'bxMailingHandler';
            }

            $handlerMailing = new $class($this, array());
            if (!($handlerMailing instanceof bxMailingInterface) or !$handlerMailing->initialize()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize mailers handler class: "' . $class . '"');
                $this->handlerMailing = false;
            } else {
                $this->handlerMailing = $handlerMailing;
            }

        }
        return $this->handlerMailing;
    }

    /**
     * Load class HandlerQuery
     * @return bool|bxQueryHandler|bxQueryInterface|null
     */
    public function loadHandlerQuery()
    {
        if (is_null($this->handlerQuery)) {
            if (!class_exists('bxQueryInterface')) {
                require_once dirname(__FILE__) . '/bxqueryhandler.class.php';
            }

            $class = $this->modx->getOption('bxsender_handler_query', null, 'bxQueryHandler');
            if ($class != 'bxQueryHandler') {
                $this->loadCustomClasses('query');
            }

            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error load QueryHandler class: "' . $class . '" not found.');
                $class = 'bxQueryHandler';
            }

            $handlerQuery = new $class($this, array());
            if (!($handlerQuery instanceof bxQueryInterface) or !$handlerQuery->initialize()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize payment QueryHandler class: "' . $class . '"');
                $this->handlerQuery = false;
            } else {
                $this->handlerQuery = $handlerQuery;
            }
        }
        return $this->handlerQuery;
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public function setToken($data = array())
    {
        $hash = false;
        if (!empty($data['token']) and !empty($data['email'])) {
            if (strlen($data['token']) == 40) {
                $array = array($data['token'], $data['email']);
                $hash = is_null($array) ? false : base64_encode($this->modx->toJSON($array));
            }
        } else {
            # $this->modx->log(modX::LOG_LEVEL_ERROR, "Error data token " . print_r($data, 1), '', __METHOD__, __FILE__, __LINE__);
        }
        return $hash;
    }

    /**
     * Возвращаем контроллер для управления подписками
     * @param array|null $data
     * @return string
     */
    public function getPageSubscribeManager($data = null)
    {
        if ($hash = $this->setToken($data)) {
            return $this->getAction('subscribemanager', $hash);
        }
        return '';
    }

    /**
     * Возвращаем контроллер отписки
     * @param array $data
     * @return string
     */
    public function getUnsubscribePage($data = array())
    {
        $hash = base64_encode($this->modx->toJSON($data));
        return $this->getAction('unsubscribe', $hash);
    }


    /**
     * Вернет путь к контроллеру
     * @param $name
     * @return string
     */
    public function getAction($name, $hash = false, $addQuery = array())
    {
        $assets = ltrim($this->config['assetsUrl'], '/');
        $file = 'index.php';

        $isAddQuery = true;

        $query = array();
        if ($hash) {
            $query[$this->key_hash] = $hash;
        }
        switch ($name) {
            case 'open':
                $file = $hash . '.png';
                $isAddQuery = false;
                break;

            default:
                break;
        }
        $assets .= 'action/' . $name . '/' . $file;
        $url = $this->modx->getOption('site_url') . $assets;
        if ($isAddQuery) {
            if (count($addQuery) > 0) {
                $query = array_merge($query, $addQuery);
            }
            if (count($query) > 0) {
                $url .= '?' . http_build_query($query);
            }
        }

        return $url;
    }


    /**
     * Подписка пользователя на рассылку во время регистрации
     * @param modUser $user
     * @param string $mode
     */
    public function createUserSubscribe(modUser $user, $mode)
    {
        if ($mode == 'new') {
            // Во время регистрация добавляем всем пользователям массив для с выбранными сегментами
            $segments = array();
            if (isset($_REQUEST['extended']) and isset($_REQUEST['extended']['bxsender']) and isset($_REQUEST['extended']['bxsender']['segments'])) {
                if (!empty($_REQUEST['extended']['bxsender']['segments'])) {
                    $segments = $_REQUEST['extended']['bxsender']['segments'];
                    $segments = is_array($segments) ? $segments : explode(',', $segments);
                    foreach ($segments as $segment_id => $checked) {
                        $segment_id = (int)$segment_id;
                        $segments[$segment_id] = (boolean)$checked;
                    }
                    $segments = array_filter($segments);
                }
            }
            $extended = $user->Profile->get('extended');
            $extended['bxsender']['segments'] = $segments;
            $user->Profile->set('extended', $extended);
        }
    }

    /**
     * Создание подписки во время подтверждения e-mail адрес
     * @param modUser $user
     * @param $mode
     * @return array|bool|string
     */
    public function createUserSubscribeActivate(modUser $user, $mode)
    {
        if ($mode == 'upd' and $user->get('active') == true) {
            $extended = $user->Profile->get('extended');
            if (isset($extended['bxsender']) and isset($extended['bxsender']['segments'])) {
                $segments = $extended['bxsender']['segments'];
                $email = $user->Profile->get('email');
                $fullname = $user->Profile->get('fullname');
                $user_id = $user->get('id');
                $data = array(
                    'email' => $email,
                    'fullname' => $fullname,
                    'user_id' => $user_id ? $user_id : 0,
                    'segments' => $segments,
                );
                $response = $this->loadAction('subscribe/userActivation', $data);
                if (!$response['success']) {
                    return $response;
                }
            }
        }
        return true;
    }

    /**
     * Создание подписки во время подтверждения e-mail адрес
     * @param modUser $user
     * @param msOrderHandler $order
     * @return array|bool|string
     */
    public function createUserSubscribeOrderCreate(modUser $user, msOrderHandler $order)
    {
        $data = $order->get();
        if (!empty($data['subscribe']) and $user->get('active') == true) {
            $segments_subscrube = $this->modx->getOption('bxsender_minishop_order_subscribe', $this->config, '1');

            if (!empty($segments_subscrube)) {

                $segments = array();
                $segments_subscrube = explode(',', $segments_subscrube);
                foreach ($segments_subscrube as $key => $segment) {
                    $segments[$segment] = 1;
                }

                $email = $user->Profile->get('email');
                $fullname = $user->Profile->get('fullname');
                $user_id = $user->get('id');
                $data = array(
                    'email' => $email,
                    'fullname' => $fullname,
                    'segments' => $segments,
                );
                $response = $this->loadAction('subscribe/hook', array('user_id' => $user_id), $data);
                if (!$response['success']) {
                    return $response;
                }
            }
        }
        return $this->success();
    }


    /**
     * Загрузка словарей
     * @param $name
     */
    public function loadLexicon($name)
    {
        $this->modx->lexicon->load('bxsender:' . $name);
    }

    /**
     * This method returns an error response
     *
     * @param string $message A lexicon key for error message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    /**
     * This method returns an success response
     *
     * @param string $message A lexicon key for success message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    protected $sendOrder = null;

    /**
     * Записываем новый заказа
     * @param $order_id
     * @param int $status
     * @param bool $email_user
     * @param bool $email_manager
     */
    public function setOrder($order_id, $status = 0, $email_user = false, $email_manager = false)
    {
        $this->sendOrder = array(
            'order_id' => $order_id,
            'status' => $status,
            'email_user' => $email_user,
            'email_manager' => $email_manager
        );
    }

    /**
     * Вернет массив с заказом
     * @return null
     */
    public function getOrder()
    {
        return $this->sendOrder;
    }

    /**
     * Устанавливаем что пользователю отправили
     */
    public function orderSendUser()
    {
        $this->sendOrder['email_user'] = false;
    }

    /**
     * Устанавливаем что менеджеру отправили
     */
    public function orderSendManager()
    {
        $this->sendOrder['email_manager'] = false;
    }

}