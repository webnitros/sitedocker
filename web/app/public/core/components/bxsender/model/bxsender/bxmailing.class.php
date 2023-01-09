<?php

class bxMailing extends xPDOSimpleObject
{
    /* @var bxSender|null $bx */
    protected $bx = null;


    /* @var array $subscribers */
    protected $subscribers = null;
    /* @var array $entry */
    protected $entry = array();

    /* @var bxMailingMember $members */
    protected $members = null;

    /* @var string|null $status */
    protected $status = null;

    /* @var boolean $saveStatus */
    protected $saveStatus = false;

    /* @var array|null $dataURLS */
    protected $dataURLS = null;

    /**
     * Loads Data
     */
    public function loadSubscribers($false = false)
    {
        if (!is_object($this->subscribers)) {
            $q = $this->xpdo->newQuery('bxSubscriber');
            $q->where(
                array(
                    'state' => 'allowed',
                )
            );
            if (!$this->subscribers = $this->getMany('Subscribers', $q)) {
                $this->subscribers = $false ? false : array();
            }
        }
        return $this->subscribers;
    }

    /**
     * Добавление в очередь сообщения
     * @param xpdo $xpdo
     * @param bxMailing $Mailing
     * @param bxSubscriber $subscriber
     * @return bool|int
     */
    static public function addQueue($xpdo, $Mailing, $subscriber = null, $queue_id = false)
    {
        $variables = array();
        if ($subscriber->get('user_id') == 0) {
            if ($user = $subscriber->loadUser()) {
                if ($user->get('id') != 0) {
                    $profile = $user->getOne('Profile');
                    // Skip inactive users
                    if (!$user->active || $profile->blocked || !$subscriber->active) {
                        return false;
                    }
                    $variables['profile'] = $profile->toArray();
                    $variables['username'] = $user->get('username');
                    $variables['user_id'] = $user->get('id');
                }
            }
        }

        // Подготовка данных отправки
        $email_to = $subscriber->email;

        // При создании сообщения эти данные должны бать заполнены
        $segment = array();

        // Set variables
        if (!empty($segment)) {
            // Add user fields
            $properties = $subscriber->get('properties');
            if (!empty($properties)) {
                $variables = array_merge($variables, $properties);
            }
        }

        /** @var bxQueue $queue */
        $data = array(
            'action' => 'query',
            'state' => 'prepare',
            'service' => 'bxsender',
            'mailing_id' => $Mailing->get('id'),
            'user_id' => $subscriber->user_id,
            'subscriber_id' => $subscriber->id,
            'email_to' => $email_to,
            'variables' => array_merge($variables, array(
                'email_to' => $email_to,
                'subscriber_token' => $subscriber->get('token'),
                'subscriber_fullname' => $subscriber->get('fullname'),
                'subscriber_email' => $subscriber->get('email'),
                'subscriber_first_name' => $subscriber->get('first_name'),
                'subscriber_middle_name' => $subscriber->get('middle_name'),
                'subscriber_last_name' => $subscriber->get('last_name'),
            )),
        );


        /* @var bxQueue $Queue */
        if ($queue_id) {
            if (!$Queue = $xpdo->getObject('bxQueue', $queue_id)) {
                $Queue = $xpdo->newObject('bxQueue');
            }
        } else {
            $Queue = $xpdo->newObject('bxQueue');
        }
        $Queue->fromArray($data);


        if ($Queue->save()) {
            return $Queue->get('id');
        }

        $errors = array();

        /* @var xPDOValidator $validator */
        $validator = $Queue->getValidator();
        if ($validator->validate() == false) {
            $messages = $validator->getMessages();
            foreach ($messages as $errorMsg) {
                $errors[$errorMsg['name']] = $errorMsg['message'];
            }
        }

        $xpdo->log(modX::LOG_LEVEL_ERROR, "Error create queue mailing: {$Mailing->get('id')}, subscriber: {$subscriber->id} message" . print_r($errors, 1), '', __METHOD__, __FILE__, __LINE__);
        return false;
    }

    /**
     * Вернет хеш для ссылки
     * @param string $url
     * @param bxQueue $queue
     * @return bool|string
     */
    public function getHashUrl($url, bxQueue $queue)
    {
        $hash = substr(base_convert(md5($url), 16, 32), 0, 12);
        if (is_null($this->dataURLS)) {
            $this->dataURLS = array();
            $q = $this->xpdo->newQuery('bxUrl');
            $q->select('hash,url');
            $q->where(array(
                'mailing_id' => $this->get('id'),
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->dataURLS[$row['hash']] = $row['url'];
                }
            }
        }
        $url = $this->doubleUrlReplacement($url);
        if (!isset($this->dataURLS[$hash])) {
            /* @var bxUrl $bxUrl */
            $bxUrl = $this->xpdo->newObject('bxUrl');
            $bxUrl->set('mailing_id', $this->get('id'));
            $bxUrl->set('subscriber_id', $queue->get('subscriber_id'));
            $bxUrl->set('hash', $hash);
            $bxUrl->set('url', $url);
            $queue->addMany($bxUrl);
            $this->dataURLS[$hash] = $url;
        }
        return $hash;
    }

    /**
     * Заменяет двой url сайта
     * @param $url
     * @return string|string[]
     */
    private function doubleUrlReplacement($url)
    {
        $site_url_normal = $this->xpdo->getOption('site_url');
        $site_url = $site_url_normal . $site_url_normal;
        if (strripos($url, $site_url) !== false) {
            $url = str_ireplace($site_url, $site_url_normal, $url);
        }
        return $url;
    }

    /**
     * Вернет true если это рассылка компонента
     * @return bool
     */
    public function isServiceBx()
    {
        return $this->getServiceName() == 'bxsender';
    }

    /**
     * Вернет true если это рассылка компонента
     * @return bool
     */
    public function getServiceName()
    {
        return strtolower($this->get('service'));
    }

    /**
     * Проверка смены статуса отправки
     */
    public function changeStatus()
    {
        $shipping_status = $this->get('shipping_status');
        if (!$this->isServiceBx() and ($shipping_status != 'process' and $shipping_status != '')) {
            // Для сторонних сервисов состояние рассылки всегда process. Чтобы нельзя было останавливать рассылку
            $this->set('shipping_status', 'process');
            $this->saveStatus = true;
        } else {
            // Для завершенных рассылок стату всегда будет completed
            if ($this->get('completed')) {
                $this->set('shipping_status', 'completed');
                $this->saveStatus = true;
            }
        }

        // Блокировка рассылки для отправки. Заблокируется генерация сообщений и их отправка
        if ($shipping_status == 'paused') {
            $this->lock();
        } else {
            $this->unlock();
        }
    }


    /** @inheritdoc */
    public function set($k, $v = null, $vType = '')
    {
        if (is_null($this->status)) {
            // Сохраняем старый статус для блокировка сохранения статуса не через процессоры
            $this->status = $this->_fields['shipping_status'];
        }
        return parent::set($k, $v, $vType);
    }


    /**
     * Установка статус для рассылки
     * @param string $status
     * @return bool
     */
    public function setStatus($status)
    {
        switch ($status) {
            case 'process':
                if (!$this->get('start')) {
                    $this->set('start', true);
                }

                // Фиксация времени начала рассылки
                if (!$this->get('start_by_time')) {
                    $this->set('start_mailing', time());
                } else {
                    $this->set('start_mailing', $this->get('start_by_timedon'));
                }

                $this->updateSubscribeCount();

                break;
            case 'completed':

                if (!$this->get('completed')) {
                    // Фиксация времени завершения рассылки
                    $this->set('completed', 1);
                    $this->set('end_mailing', time());
                }
                break;
            case 'paused':
                // Фиксация времени установки паузы
                $this->set('paused_mailing', time());
                break;
            default:
                break;
        }


        $this->saveStatus = true;
        $this->set('shipping_status', $status);
        $this->changeStatus();
        return $this->save();
    }


    public function isCompleted()
    {
        return $this->get('completed');
    }

    public function isProcess()
    {
        return $this->get('shipping_status') == 'process';
    }

    /**
     * Обновление количество подписок
     */
    public function updateSubscribeCount()
    {
        // Разрешить обновление только если рассылку не завершена или не находится в процессе
        if (!$this->isCompleted() and !$this->isProcess()) {
            $subscribers_count = 0;
            if ($segment_ids = $this->getSegmentsIds()) {
                $subscribers_count = $this->getCountSubscribers($segment_ids);
            }
            $this->set('subscribers_count', $subscribers_count);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
            if (empty($this->get('service'))) {
                $this->set('service', 'bxsender');
            }
        } else {
            $this->set('updatedon', time());
        }

        $this->changeStatus();

        // Возврат старого статус если пытались сохранить не через функцию
        if (!$this->saveStatus) {
            $this->set('shipping_status', $this->status);
        }
        return parent::save();
    }

    /**
     * Вернет true если влючены метки для сообщения
     * @return bool
     */
    public function isUTM()
    {
        return (boolean)$this->get('utm');
    }

    /**
     * Вернет строку для меток
     * @return null|string
     */
    public function getUTM()
    {
        $array = $this->get(array('utm_source', 'utm_medium', 'utm_campaign'));
        $array = array_map('trim', $array);
        return array_filter($array);
    }

    /**
     * Вернет строку для меток
     * @return null|string
     */
    public function getUTMstr()
    {
        $array = $this->getUTM();
        return http_build_query($array);
    }


    /**
     * Проверит почту по SMTP
     * @return null|string
     */
    public function getting()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        if (!$ReturnPath = $bxSender->loadReturnPath()) {
            $message = "Error get ReturnPath";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message, '', __METHOD__, __FILE__, __LINE__);
            return $message;
        }
        return $ReturnPath->getting();
    }

    /**
     * Проверка полученых писем
     * @return null|string
     */
    public function reading()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        if (!$ReturnPath = $bxSender->loadReturnPath()) {
            $message = "Error get ReturnPath";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message, '', __METHOD__, __FILE__, __LINE__);
            return $message;
        }
        return $ReturnPath->reading();
    }


    /**
     * Установка статуса что рассылка завершена если в очереди отсутствую сообщения в состоянии  prepare или query
     */
    public function completed()
    {
        return $this->setStatus('completed');
    }


    /**
     * Load bxMailingMember
     * @return bool|bxMailingMember[]
     */
    public function loadSegmentMember()
    {
        if (!is_array($this->members) and !is_bool($this->members)) {
            if (!$this->members = $this->getMany('Members')) {
                $this->members = false;
            }
        }
        return $this->members;
    }


    /**
     * Вернет сгенерированное сообщение для рассылки
     * @return mixed|string
     */
    public function parserMessage()
    {
        /* @var bxQueue $bxQueue */
        $bxQueue = $this->xpdo->newObject('bxQueue');
        $bxQueue->set('mailing_id', $this->get('id'));
        $bxQueue->set('hash', 'demo');
        $bxQueue->set('testing', true);
        if ($content = $bxQueue->content()) {
            return $content;
        }
        return $this->get('message');
    }


    /**
     * Проверяем чтобы рассылка была не отложена
     * @return boolean
     */
    public function isStartByTime()
    {
        $start_by_time = false;
        if ($this->isServiceBx()) {
            $start_by_time = $this->get('start_by_time');
        }
        return $start_by_time;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('shipping_status');
    }

    /**
     * Вернет сгенерированное сообщение для рассылки
     * @return mixed|string
     */
    public function isMailingAllowed()
    {
        $allowed = true;
        // Отправка разрешена только на статусе process
        if ($this->getStatus() == 'process') {
            if ($this->isStartByTime()) {

                // Если рассылка отложенная то проверяем дату наступления рассылки
                $current = time(); // Текущее время
                $start_mailing = strtotime($this->get('start_mailing')); // Дата после которой рассылка будет разрешена
                if ($current > $start_mailing) {
                    $allowed = false;
                }

            } else {
                $allowed = false;
            }
        }
        return $allowed;
    }

    /* @var bxSender|null|boolean $bxs */
    protected $bxs = null;

    /**
     * @return bool|bxSender|null
     */
    public function loadSender()
    {
        if (!is_null($this->bxs) || !($this->bxs instanceof bxSender)) {
            $this->bxs = $this->bx =$this->xpdo->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        }
        return $this->bxs;
    }

    /* @var string|null $pathblocking */
    protected $pathblocking = null;


    /**
     * Вернет путь к файлу блокировки рассылки
     * @return null|string
     */
    public function getPathBlocking()
    {
        if (is_null($this->pathblocking)) {
            $bxsender = $this->loadSender();
            $mailing_id = $this->get('id');
            $this->pathblocking = $bxsender->config['blockingPath'] . 'mailing_' . $mailing_id . '.lock';
        }
        return $this->pathblocking;
    }

    /**
     * Блокировка рассылки
     */
    public function lock()
    {
        if ($filePath = $this->getPathBlocking()) {
            if (!file_exists($filePath)) {
                $cache = $this->xpdo->getCacheManager();
                $cache->writeFile($filePath, time());
            }
        }
    }

    /**
     * Разблокировка рассылки
     */
    public function unlock()
    {
        if ($filePath = $this->getPathBlocking()) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

    }

    /**
     * Проверка паузы у рассылки
     * @return mixed|string
     */
    public function isBlocking()
    {
        $filePath = $this->getPathBlocking();
        if (file_exists($filePath)) {
            return true;
        }
        return false;
    }


    /**
     * Проверка требования для отправки сообщений
     * - Рассылка должна находится в состоянии "Рассылка"
     * - Рассылка не должна быть заблокирована для отправки
     * - Лимит сообщений не должен превышать количество сообщений установленый в интервале времени
     *
     * @throws Exception
     */
    public function fulfillConditionsSending()
    {
        // Проверка разрешения на рассылку
        if ($this->isMailingAllowed()) {
            throw new Exception("Рассылку необходимо перевести в состояние отправки");
        }


        if ($this->isBlocking()) {
            throw new Exception("Рассылка находить в состоянии паузы");
        }

        if (!$Transport = $this->bx->loadMailSender()) {
            throw new Exception("Не удалось загрузить траспорт сообщений");
        }


        // Проверка интервала и лимита отправки сообщений
        $Transport->enforceExecutionRequirements();
    }


    /**
     * @param bxMailSender $Transport
     * @return bool

    static function isSendingLimitReached($Transport)
     * {
     * $Interval = $Transport->getInterval(); // Интервальность отправки
     * $Limit = $Transport->getLimit(); // Лимит сообщений
     * $Sent = $Transport->getSent(); // Количество отправленных сообщений
     * $elapsed_time = $Transport->elapsedTime();
     *
     *
     * // Проверим Количество отправленных и Лимит
     * if ($Sent >= $Limit) {
     * // Пройденное время должно быть меньше чем максимальное количество писем за интервал
     * if ($elapsed_time <= $Interval) {
     * return true;
     * }
     * // Когда заданный интервал исчерпан сбросывам счетчик для следующейх отправок
     * $Transport->resetMailerLog();
     * }
     * return false;
     * } */


    /**
     *
     * @return array
     */
    public function getSubscribeStatistic()
    {
        $subscriber_count = 0;
        $segments = $this->getSegmentsIds();

        // Вернет общее количество подписчиков в выбранном сегменте
        $q = $this->xpdo->newQuery('bxSubscriberMember');
        $q->select("(SELECT COUNT(DISTINCT Subscriber.subscriber_id) FROM {$this->xpdo->getTableName('bxSubscriberMember')} as Subscriber WHERE Subscriber.segment_id = bxMailingMember.segment_id) as subscriber_count");
        $q->where(array(
            'bxMailingMember.mailing_id' => $this->get('id'),
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $segment_id = $row['segment_id'];
                $count = (int)$row['subscriber_count'];
                $subscriber_count = $subscriber_count + $count;
                $data['segments'][$segment_id] = $count;
            }
        }
        $data['subscriber_count'] = $subscriber_count;
        return $data;
    }

    /* @var null|array $segments_ids */
    protected $segments_ids = null;

    /**
     * Вернет общее количество подписчиков в выбранных сегментах и дополнительно все ID сегментов
     * @return array
     */
    public function getSegmentsIds()
    {
        if (is_null($this->segments_ids)) {
            $segments_ids = array();
            $q = $this->xpdo->newQuery('bxMailingMember');
            $q->select("segment_id");
            $q->where(array(
                'mailing_id' => $this->get('id'),
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $segments_ids[] = $row['segment_id'];
                }
            }
            if (count($segments_ids) == 0) {
                $this->segments_ids = false;
            } else {
                $this->segments_ids = $segments_ids;
            }
        }
        return $this->segments_ids;
    }


    /**
     * @param $segments
     * @return xPDOQuery
     */
    public function getCriteriasSubscribers($segments, $select = false)
    {
        $select = $select ? $select : array('id', 'email', 'fullname', 'first_name', 'middle_name', 'last_name', 'user_id');

        $q = $this->xpdo->newQuery('bxSubscriber');
        $q->select($this->xpdo->getSelectColumns('bxSubscriber', 'bxSubscriber', '', $select));
        $q->innerJoin('bxSubscriberMember', 'Member', 'Member.subscriber_id = bxSubscriber.id');
        $q->leftJoin('bxSegment', 'Segment', "Segment.id = Member.segment_id");
        $q->where(array(
            'Member.segment_id:IN' => $segments,
            'bxSubscriber.active' => 1,
            'Segment.active' => 1,
        ));
        $q->groupby('id');
        return $q;
    }


    /**
     * Вернет количество получателей
     * @param array|null $segments
     * @return int
     */
    public function getCountSubscribers($segments = null)
    {
        if (is_null($segments)) {
            $segments = $this->getSegmentsIds();
        }
        $q = $this->getCriteriasSubscribers($segments);
        return $this->xpdo->getCount('bxSubscriber', $q);
    }


    /**
     * Вернет true если рассылка была заблокирована
     * @return bool
     */
    public function isMailingProhibited()
    {
        if ($this->isMailingAllowed() or $this->isBlocking()) {
            return true;
        }
        return false;
    }

    /* @var null|int $countSubscriber */
    private $countSubscriber = null;


    /**
     * Подписок добавленых в очередь
     * @return int
     */
    public function getCountQueue()
    {
        return $this->xpdo->getCount('bxQueue', array('mailing_id' => $this->get('id')));
    }

    /**
     * Получаем общее количество добавленных подписок в очереди и сравниваем с общим количество для рассылки
     * @param bxMailing $Mailing
     * @return bool
     */
    static private function isTotalSubscriptionsLimit(bxMailing $Mailing)
    {
        $countQueue = $Mailing->xpdo->getCount('bxQueue', array(
            'mailing_id' => $Mailing->get('id'),
            'action' => '',
        ));
        // Проверяем сообщения в очереди, если все сообщениея перешли в статус подготовки то устанавливаем метку о том что все сообщения в очереди
        if ($countQueue == 0) {
            // Записываем количество сообщений в очереди для рассылки
            $Mailing->set('queue_created', true);
            return $Mailing->save();
        }
        return false;
    }

    /**
     * Добавление в очередь
     *
     * @param bxMailing|null $Mailing
     * @param bxSender $bxSender
     * @return bool вернет false если сущетствуют подписки не добавленные в очередь
     */
    static function addQueues(bxMailing $Mailing, $bxSender = null)
    {
        // Сообщения в очереди
        if ($Mailing->get('queue_created')) {
            return true;
        }

        $bxSender->enforceExecutionLimit($bxSender->timer);
        if ($Mailing->isMailingProhibited()) {
            return true;
        }


        // Проверяем чтобы очередь еще небыла создана
        if ($Mailing->isTotalSubscriptionsLimit($Mailing)) {
            return true;
        }

        $q = $bxSender->modx->newQuery('bxQueue');
        $q->select('id,subscriber_id');
        $q->where(array(
            'mailing_id' => $Mailing->get('id'),
            'action' => '',
        ));
        $q->limit(1000);
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $queue_id = $row['id'];
                $subscriber_id = $row['subscriber_id'];

                /* @var bxSubscriber $Subscriber */
                if ($Subscriber = $bxSender->modx->getObject('bxSubscriber', $subscriber_id)) {
                    $Mailing->addQueue($Mailing->xpdo, $Mailing, $Subscriber, $queue_id);
                } else {
                    // Если подписчик небыл найден то удаляем сообщение из очереди
                    $c = $bxSender->modx->newQuery('bxQueue');
                    $c->command('DELETE');
                    $c->where(array('id' => $queue_id));
                    $c->prepare();
                    $c->stmt->execute();
                }
                $bxSender->enforceExecutionLimit($bxSender->timer);
            }
        }

        // Сравнения количество подписчиков после добавления в очередь
        if ($Mailing->isTotalSubscriptionsLimit($Mailing)) {
            return true;
        }

        return false;
    }


    /**
     * Получаем общее количество добавленных подписок в очереди и сравниваем с общим количество для рассылки
     * @param bxMailing $Mailing
     * @return bool
     */
    static private function isSubscriptionsPrepareLimit(bxMailing $Mailing)
    {
        // Сообщения сгенерированы
        if ($Mailing->get('queue_preapre')) {
            return true;
        }

        $countQueuePrepare = $Mailing->xpdo->getCount('bxQueue', array(
            'mailing_id' => $Mailing->get('id'),
            'state' => 'prepare'
        ));

        if ($countQueuePrepare == 0) {
            // Ставим метку что все сообщения сгенерированы
            $Mailing->set('queue_preapre', true);
            return $Mailing->save();
        }
        return false;
    }


    /**
     * Добавление сообщений в очередь
     *
     * @param bxMailing $Mailing
     * @param bxSender|null $bxSender
     * @return bool
     * @throws Exception
     */
    static function generationMessage(bxMailing $Mailing, bxSender $bxSender = null)
    {
        $bxSender->enforceExecutionLimit($bxSender->timer);

        if ($Mailing->isMailingProhibited()) {
            return true;
        }

        if ($Mailing->isSubscriptionsPrepareLimit($Mailing)) {
            return true;
        }

        /* @var bxQueue $queue */
        $q = $Mailing->xpdo->newQuery('bxQueue');
        $q->where(array(
            'mailing_id' => $Mailing->get('id'),
            'state' => 'prepare'
        ));
        $q->limit(500);
        if ($queueList = $Mailing->xpdo->getCollection('bxQueue', $q)) {
            foreach ($queueList as $queue) {
                if (!$queue->isProcessed()) {
                    $queue->action('query');
                }
                $bxSender->enforceExecutionLimit($bxSender->timer);
            }
        }

        if ($Mailing->isSubscriptionsPrepareLimit($Mailing)) {
            return true;
        }
        return false;
    }


    /**
     * Перевод рассылки в статус рассылка завершена
     * @param bxMailing $Mailing
     * @return bool
     */
    static private function isSubscriptionsQueuedWaitingLimit(bxMailing $Mailing)
    {
        // Сообщения отправлены
        if ($Mailing->get('completed')) {
            return true;
        }

        // Перевод в статус рассылка завершена если в очереди отсутствуют сообщения для рассылки waiting и prepare
        $countQueuePrepare = $Mailing->xpdo->getCount('bxQueue', array(
            'mailing_id' => $Mailing->get('id'),
            'state:IN' => array('waiting', 'prepare')
        ));
        if (!$countQueuePrepare) {
            return $Mailing->completed();
        }
        return false;
    }


    /**
     * Рассылка сообщений из очереди
     *
     * @param bxMailing $Mailing
     * @param bxSender $bxSender
     * @return bool
     * @throws Exception
     */
    static function sendingMessage(bxMailing $Mailing, bxSender $bxSender)
    {
        $bxSender->enforceExecutionLimit($bxSender->timer);
        if ($Mailing->isMailingProhibited()) {
            return true;
        }

        if ($Mailing->isSubscriptionsQueuedWaitingLimit($Mailing)) {
            return true;
        }


        // Проверка требования для отправки сообщений
        $Mailing->fulfillConditionsSending();


        // Получаем максимальное количество отправляемых сообщений чтобы не тянуть все сообщения разом
        $emails = (int)$bxSender->loadMailSender()->get('frequency_emails');
        $interval = (int)$bxSender->loadMailSender()->get('frequency_interval');

        $limit = $emails / $interval;
        $limit = ceil($limit);

        /* @var bxQueue $queue */
        $q = $Mailing->xpdo->newQuery('bxQueue');
        $q->where(array(
            'mailing_id' => $Mailing->get('id'),
            'state' => 'waiting'
        ));
        $q->limit($limit);
        if ($queueList = $Mailing->xpdo->getCollection('bxQueue', $q)) {
            foreach ($queueList as $queue) {
                if (!$queue->isProcessed()) {
                    // Проверка требования для отправки сообщений
                    $Mailing->fulfillConditionsSending();

                    // Записываем объект рассылки для очереди
                    $queue->setMailing($Mailing);
                    $queue->action('send');
                    $bxSender->enforceExecutionLimit($bxSender->timer);
                }
            }
        }

        if ($Mailing->isSubscriptionsQueuedWaitingLimit($Mailing)) {
            return true;
        }

        return false;
    }


    /**
     * @param bxSender $bxSender
     * @return bool|string
     */
    public function runProcessMailing(bxSender $bxSender)
    {
        /* @var bxSender $bxSender */
        $this->bx = $bxSender;
        $response = true;
        try {
            // Добавление в очередь
            if ($this->addQueues($this, $bxSender)) {

                // Генерация сообщений в очередь
                if ($this->generationMessage($this, $bxSender)) {
                    $this->sendingMessage($this, $bxSender);
                }
            }
        } catch (ExceptionSending $e) {
            #$bxSender->modx->log(modX::LOG_LEVEL_ERROR, "Paused ", '', __METHOD__, __FILE__, __LINE__);
            return true;
        } catch (Exception $e) {
            $response = "Error: {$e->getMessage()} mailing_id: {$this->get('id')}";
        }
        return $response;
    }

}