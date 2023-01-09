<?php

interface bxQueryInterface
{
    /**
     * initialize class
     *
     * @return array|boolean $response
     */
    public function initialize();

    /**
     * Генерация контента
     * @param bxQueue $queue
     * @param bool $save
     * @return mixed
     */
    public function process(bxQueue $queue, $save = true);


    /**
     * Добавление переменных в шаблон
     * @return boolean
     */
    public function addVarables();

}

class bxQueryHandler implements bxQueryInterface
{
    private $subscriber_fullname = 'demo';
    private $subscriber_email = 'demo@demo.ru';

    /* @var bxSender|null $bx */
    public $bx = null;

    /* @var boolean $directLinks */
    public $directLinks = false;

    /* @var bxQueue $queue */
    protected $queue;

    protected $variables = array();

    /* @var string|null $hash */
    protected $hash = null;

    /* @var string|null $subject */
    protected $subject = null;

    /* @var string|null $message */
    protected $message = null;

    /**
     * @param bxSender $bx
     */
    function __construct(bxSender & $bx)
    {
        $this->bx = &$bx;
    }

    /**
     * Инициализация процессов
     * @return bool
     */
    public function initialize()
    {
        $this->bx->loadPdoTools();
        if (!$this->bx->loadEmogrifier()) {
            $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error load class emogrifier", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        if (!$this->bx->loadClassPquery()) {
            $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error load class pquery", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        return true;
    }

    /**
     * Проверка на отписку пользователя от рассылки и проверка на ошибки доставки
     * @return bool
     */
    public function verificationDispatchRequirements()
    {
        if ($this->queue->isUnSubscribed()) {
            $state = 'unsubscribed';
        } else if ($this->queue->isUnDeliverable()) {
            $state = 'undeliverable';
        } else {
            $state = 'waiting';
        }
        return $state;
    }

    /* @inheritdoc */
    public function process(bxQueue $queue, $save = true)
    {
        $this->resetQueue($queue);


        $state = $this->verificationDispatchRequirements();

        if ($state == 'waiting') {
            $this->setVariables($this->queue->get('variables'));

            // bxSenderBeforeMessage
            $message = $this->getMessage();
            

            if (empty($message)) {
                $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error empty message", '', __METHOD__, __FILE__, __LINE__);
                $state = 'error';
            } else {
                $subject = $this->getSubject();
                $this->queue->set('variables', $this->getVariables());
                $this->queue->set('email_body', $message);
                $this->queue->set('email_subject', $subject);
                $this->queue->set('action', 'mail');
            }
        }


        if (!$save) {
            return true;
        }

        $result = $this->queue->save();
        $data = array('state' => $state);
        if ($state != 'waiting') {
            $data['completed'] = 1;
        }
        $this->queue->operation('update', $data);
        return $result;
    }

    /**
     * Сброс переменных для сообщения
     *
     * @param bxQueue $queue
     */
    private function resetQueue(bxQueue $queue)
    {
        $this->queue = $queue;
        $this->hash = $this->queue->getHash();
        $this->variables = array();
        $this->subject = $this->queue->loadMailing()->get('subject');
        $this->message = $this->queue->loadMailing()->get('message');
    }


    /**
     * Вернет тему сообщения
     * @return string
     */
    protected function getSubject()
    {
        if (!$this->isServiceBX()) {
            return $this->queue->get('email_subject');
        } else {
            return $this->bx->pdoFetch->getChunk('@INLINE ' . $this->subject, $this->getVariables());
        }
    }

    /**
     * Вернет ссылку на сайт
     * @return mixed
     */
    protected function getSiteUrl()
    {
        return $this->bx->modx->getOption('site_url');
    }


    /**
     * @return bool
     */
    protected function isServiceBX()
    {
        $service = strtolower($this->queue->get('service'));
        return $service == 'bxsender' or empty($service);
    }

    /**
     * @return null|string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Добавление дополнительных переменных в шаблон письма
     * @return array
     */
    public function addVarables()
    {
        $site_url = $this->getSiteUrl();
        $data = array(
            'site_url' => $site_url,
            'assets_url' => $site_url . ltrim($this->bx->modx->getOption('assets_url'), '/'),
            $this->bx->key_hash => $this->getHash(),
            'emailsender' => $this->bx->modx->getOption('emailsender'),
            'unsubscribe_page' => $this->getUnsubscribePage(),
            'subscribe_manager_page' => $this->getSubscribeManagerPage(),
            'open_browser_link' => $this->getOpenBrowser(),
            'imageviewcount' => $this->getImageViewCount(),
            'mailing_service' => $this->queue->get('service'),
            'queue_testing' => $this->queue->get('testing'),
            'queue_user_id' => $this->queue->get('user_id'),
            'queue_mailing_id' => $this->queue->get('mailing_id'),
            'queue_subscriber_id' => $this->queue->get('subscriber_id'),
            'queue_email_subject' => $this->queue->get('email_subject'),
            'utm_enable' => $this->queue->loadMailing()->get('utm'),
        );
        $data = array_merge($data, $this->queue->loadMailing()->getUTM());
        return $data;
    }

    /**
     * Страница управления подписками
     * @return string
     */
    protected function getSubscribeManagerPage()
    {
        $variables = $this->queue->get('variables');
        return $this->bx->getPageSubscribeManager(array(
            'email' => $this->queue->get('email_to'),
            'token' => isset($variables['subscriber_token']) ? $variables['subscriber_token'] : '',
        ));
    }

    /**
     * Страница быстрой отписки
     * @return string
     */
    protected function getUnsubscribePage()
    {
        return $this->bx->getUnsubscribePage(array_merge($this->queue->getHash(true), array(
            'email' => $this->queue->get('email_to'),
        )));
    }


    /**
     * Ссылка открыть в браузере
     * @return mixed
     */
    protected function getOpenBrowser()
    {
        return $this->bx->getAction('openbrowser', $this->getHash());
    }

    /**
     * Ссылка открыть в браузере
     * @return mixed
     */
    protected function getImageViewCount()
    {
        $url = $this->bx->getAction('open', $this->getHash());
        return '<img alt="counter" src="' . $url . '"/>';
    }


    /**
     * Для отправки тестовы сообщений
     * Заполняется fullname и email
     */
    protected function isTesting()
    {
        if ($this->queue->isTestingSending()) {
            if ($Subscriber = $this->bx->modx->getObject('bxSubscriber', array('email' => $this->queue->get('email_to')))) {
                $subscriber_fullname = !empty($Subscriber->get('fullname')) ? $Subscriber->get('fullname') : $this->subscriber_fullname;
                $subscriber_email = $Subscriber->get('email');
            } else {
                if ($this->bx->modx->user->get('id') != 0) {
                    $subscriber_fullname = $this->bx->modx->user->Profile->get('fullname');
                    $subscriber_email = $this->bx->modx->user->Profile->get('email');
                } else {
                    $subscriber_fullname = $this->subscriber_fullname;
                    $subscriber_email = $this->subscriber_email;
                }
            }
            $this->setVariable('subscriber_fullname', $subscriber_fullname);
            $this->setVariable('subscriber_email', $subscriber_email);
        }
    }


    /**
     * После подготовки сообщения для отправки
     * Заполняется fullname и email
     */
    public function afterPreparingMessage($content = '')
    {
        if (!empty($content)) {
            $content = $this->replaceContent($content);
            // Для сторонних сервисов изображение для отслеживания открытий добавляется в конец письма
            if (!$this->isServiceBX()) {
                $content .= $this->getVariable('imageviewcount');
            }
        }
        $this->queue->set('email_body_text', $this->getTextVersion($content));
        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    private function getTextVersion($content)
    {
        $content = (mb_detect_encoding($content, 'UTF-8', true)) ? $content : utf8_encode($content);
        $internalErrors = libxml_use_internal_errors(true);
        $content = $this->bx->loadClassHtml2Text()->convert($content);
        libxml_use_internal_errors($internalErrors);
        return $content;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        // Дополнительные переменные для шаблона
        $variables = $this->addVarables();
        // Событие для добавления своих перменые в шаблон
        /*$this->bxs->invokeEvent('bxOnBeforeAddVariables', array(
            'variables' => $variables,
            'queue' => &$this->queue,
        ));*/
        $this->setVariables($variables);
        $this->isTesting();
        if (!$this->isServiceBX()) {
            // Для сообщений из сторонних сервисов тело сообщение не обрабатывается
            $content = $this->queue->getContentHtml();
        } else {
            $content = $this->message;
            if (!empty($content)) {
                // TODO необоходимо выкидывать ошибку в случае если произошла ошибка
                // Заполняем контентом
                $content = $this->writingVariablesToTemplate($content, $this->getVariables());
                try {
                    // Весь CSS преобразовываем в style
                    $this->bx->loadEmogrifier()->setHtml($content);
                    $content = $this->bx->loadEmogrifier()->emogrify();
                } catch (InvalidArgumentException $e) {
                    $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error load tempalte mailing_id: {$this->queue->get('mailing_id')} " . $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
                    $content = $this->bx->modx->lexicon('bxsender_mailing_error_content_template', array('content' => $this->message));
                }
            }
        }

        return $this->afterPreparingMessage($content);
    }


    /**
     * Записываем полученные переменные в шаблон с обработкой тегов MODX и тегов Fenom
     * @param string $content
     * @param array $pls
     * @return mixed
     */
    public function writingVariablesToTemplate($content, $pls = array())
    {
        $content = $this->bx->pdoFetch->getChunk('@INLINE ' . $content, $pls);
        $this->bx->modx->getParser()->processElementTags('', $content, true, false, '[[', ']]', array(), 10);
        $this->bx->modx->getParser()->processElementTags('', $content, true, true, '[[', ']]', array(), 10);
        return $content;
    }

    /**
     * Поиск технических ссылок чтобы не добавлять не какую лишнию информаицю
     * @param $url
     * @return bool
     */
    protected function technicalReference($url)
    {
        if (strripos($url, $this->bx->config['assetsUrl']) === false) {
            return true;
        }
        return false;
    }

    /**
     * Проверка валидности URL
     * @param $url
     * @return bool
     */
    protected function isValidateUrl($url)
    {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }

    /**
     * Поиск ссылки в базе данных
     *
     * @param $url
     * @return string вернет ссылку на контроллер
     */
    protected function findURLHash($url)
    {
        $hash = $this->queue->loadMailing()->getHashUrl($url, $this->queue);
        return $this->bx->getAction('clicks', $this->getHash(), array('hash_link' => $hash));
    }


    /**
     * Добавление UTM меток для ссылки
     * @param $url
     * @return mixed
     */
    protected function addUTM($url, $separator = '?')
    {
        if ($this->queue->loadMailing()->isUTM()) {
            $utm = $this->queue->loadMailing()->getUTMstr();
            if (strripos($url, '?') !== false) {
                $separator = '&';
            }
            $url .= $separator . $utm;
        }
        return $url;

    }


    /**
     * Добавление HASH в
     * @param $url
     * @return mixed
     */
    protected function addHASH($url, $separator = '?')
    {
        if (strripos($url, '?') !== false) {
            $separator = '&';
        }
        $url .= $separator . $this->bx->key_hash . '=' . $this->getHash();
        return $url;
    }


    /**
     * Форматирование письма
     * @param $output
     * @return string
     */
    protected function replaceContent($output)
    {
        $dom = $this->bx->loadClassPquery()->parseStr($output);
        $dom = $this->replaceHref($dom);
        $dom = $this->replaceSrc($dom);
        return $dom->html();
    }

    /**
     * Замена ссылок
     * @param pQuery\DomNode $dom
     * @return $dom
     */
    protected function replaceHref($dom)
    {
        $urls = $dom->query('a');
        if (count($urls) > 0) {
            foreach ($urls as $url) {
                if ($url->href) {
                    $str = $url->href;
                    if ($this->isValidateUrl($str)) {
                        if ($this->technicalReference($str)) {
                            $str = $this->addUTM($url->href);
                            if ($this->directLinks) {
                                // Добавление hash к ссылкам
                                $str = $this->addHASH($url->href);
                            } else {
                                // Замена ссылок на контроллеры
                                $str = $this->findURLHash($str);
                            }
                            $url->href = $str;
                        }
                    }
                }
            }
        }
        return $dom;
    }


    /**
     * Замена ссылок
     * @param pQuery\DomNode $dom
     * @return $dom
     */
    protected function replaceSrc($dom)
    {
        $images = $dom->query('img');
        if (count($images) > 0) {
            foreach ($images as $img) {
                if ($img->src) {
                    // пропускать все изображения с классом not_replace
                    if (isset($img->class) and strripos($img->class, 'not_replace') !== false) {
                        continue;
                    }
                    $src = $img->src;
                    $str = substr($src, 0, 4);
                    if ($str != 'http') {
                        $img->src = $this->getSiteUrl() . $src;
                    }
                }
            }
        }
        return $dom;
    }


    /**
     * Get a specific property.
     * @param string $k
     * @param mixed $default
     * @return mixed
     */
    public function getVariable($k, $default = null)
    {
        return array_key_exists($k, $this->variables) ? $this->variables[$k] : $default;
    }

    /**
     * Get a specific property.
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set a property value
     *
     * @param string $k
     * @param mixed $v
     * @return void
     */
    public function setVariable($k, $v)
    {
        $this->variables[$k] = $v;
    }

    /**
     * Set the runtime properties for the processor
     * @param array $properties The properties, in array and key-value form, to run on this processor
     * @return void
     */
    public function setVariables($properties = array())
    {
        $variables = !empty($properties) ? array_merge($this->variables, $properties) : $this->variables;
        $new = array();
        foreach ($variables as $field => $value) {
            $new[$field] = $value;
        }
        $this->variables = $new;
    }

    /**
     * Completely unset a property from the properties array
     * @param string $key
     * @return void
     */
    public function unsetVariable($key)
    {
        unset($this->variables[$key]);
    }
}