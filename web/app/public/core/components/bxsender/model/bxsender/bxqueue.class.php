<?php

class bxQueue extends xPDOSimpleObject
{
    /* @var boolean $showTemplate */
    public $showTemplate = false;

    /* @var bxSender $bxs */
    public $bx = null;

    /* @var bxSubscriber $subscriber */
    protected $subscriber = null;

    /* @var bxMailing $mailing */
    protected $mailing = null;

    /* @var bxUnDeliverable $undeliverable */
    protected $undeliverable = null;

    /* @var bxUnDeliverable $undeliverableemail */
    protected $undeliverableemail = null;

    /**
     * @return bxSender|null|object
     */
    protected function loadSender()
    {
        if (!is_object($this->bx) || !($this->bx instanceof bxSender)) {
            $bx = $this->xpdo->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
            if ($bx instanceof bxSender) {
                $this->bx = $bx;
            }
        }
        return $this->bx;
    }


    /**
     * Loads bxSubscriber
     * @return bxSubscriber|null
     */
    public function loadSubscriber($false = false)
    {
        if (!is_object($this->subscriber) || !($this->subscriber instanceof bxSubscriber)) {
            if (!$this->subscriber = $this->getOne('Subscriber')) {
                $this->subscriber = $false ? false : $this->xpdo->newObject('bxSubscriber');
            }
        }
        return $this->subscriber;
    }

    /**
     * Load bxUnDeliverable
     * @return bxUnDeliverable|null
     */
    public function loadUnDeliverable($false = false)
    {
        if (!is_object($this->undeliverable) || !($this->undeliverable instanceof bxUnDeliverable)) {
            if (!$this->undeliverable = $this->getOne('UnDeliverable')) {
                $this->undeliverable = $false ? false : $this->xpdo->newObject('bxUnDeliverable');
            }
        }
        return $this->undeliverable;
    }

    /**
     * Load bxUnDeliverable
     * @return bxUnDeliverable|null
     */
    public function loadUnDeliverableEmail($false = false)
    {
        if (!is_object($this->undeliverableemail) || !($this->undeliverableemail instanceof bxUnDeliverable)) {
            if (!$this->undeliverableemail = $this->getOne('UnDeliverableEmail')) {
                $this->undeliverableemail = $false ? false : $this->xpdo->newObject('bxUnDeliverable');
            }
        }
        return $this->undeliverableemail;
    }

    /**
     * Loads Queue
     * @return null|bxMailing
     */
    public function loadMailing($false = false)
    {
        if (!is_object($this->mailing) || !($this->mailing instanceof bxMailing)) {
            if (!$this->mailing = $this->getOne('Mailing')) {
                $this->mailing = $false ? false : $this->xpdo->newObject('bxMailing');
            }
        }
        return $this->mailing;
    }

    /**
     * Записываем Объект рассылки чтобы учитывать количество отправленных сообщений и не повторять запросы на получение рассылки
     * @param bxMailing $Mailing
     * @return bool
     */
    public function setMailing(bxMailing $Mailing)
    {
        if ($Mailing instanceof bxMailing) {
            $this->mailing = $Mailing;
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isSend()
    {
        switch ($this->get('state')) {
            case 'sent':
            case 'queued':
            case 'scheduled':
            case 'invalid':
            case 'unsubscribed':
            case 'undeliverable':
                return false;
                break;
            default:
                break;
        }
        return true;
    }

    /**
     * Состав полей для текущей рассылку учитывает уникальный:
     * - емаил адрес
     * - id рассылки
     * - id подписка
     * @return string|array
     */
    public function getHash($returnArray = false)
    {
        $data = array(
            'queue_id' => $this->get('id'),
            'mailing_id' => $this->get('mailing_id'),
            'subscriber_id' => $this->get('subscriber_id'),
        );
        if ($returnArray) {
            return $data;
        }
        return base64_encode($this->xpdo->toJSON($data));
    }


    /** {inheritDoc} */
    public function setContentHtml()
    {
        $email_body = $this->get('email_body');
        if (!empty($email_body)) {
            $email_body = $this->xpdo->toJSON(array('html' => $email_body));
            $this->set('email_body', $email_body);
        }
    }

    /** {inheritDoc} */
    public function getContentHtml()
    {
        $html = '';
        $email_body = $this->get('email_body');
        if (!empty($email_body)) {
            $content = $this->xpdo->fromJSON($email_body);
            if (isset($content['html'])) {
                $html = $content['html'];
            } else {
                $html = $content;
            }
        }
        return $html;
    }


    /** {inheritDoc} */
    public function save($cacheFlag = null)
    {
        if (!$this->isNew()) {
            $this->set('updatedon', time());
        } else {
            $this->set('createdon', time());
        }
        $this->setContentHtml();
        return parent::save($cacheFlag);
    }


    /**
     * Удаление логов
     * @param array $ancestors
     * @return bool
     */
    public function remove(array $ancestors = array())
    {
        $queue_id = $this->get('id');
        $sql = "DELETE FROM {$this->xpdo->getTableName('bxQueue')} WHERE `id` = '$queue_id';";
        $sql .= "DELETE FROM {$this->xpdo->getTableName('bxQueueLog')} WHERE `queue_id` = '$queue_id';";
        $this->xpdo->exec($sql);
        return true;
    }


    /**
     * Автоматическое переключение действий в зависимости от состояния
     */
    public function autoActions()
    {
        $action = $this->get('action');
        $state = $this->get('state');

        $result = false;

        switch ($action) {
            case 'query':
                switch ($state) {
                    case 'waiting':
                    case 'prepare':
                        $result = $this->action('query');
                        break;
                    default:
                        break;
                }

                break;
            case 'mail':
                switch ($state) {
                    case 'waiting':
                        $result = $this->action('send');
                        break;
                    case 'sent':
                        $result = $this->action('state');
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * Проверям что сообщение не находится в очереди
     * @return bool
     */
    public function isProcessed()
    {
        // В течении 1 минут сообщене нелязя будет открыть повторно
        $unlock_time = strtotime('-1 minutes', time());
        $result = (boolean)$this->xpdo->getCount($this->_class, array(
            'id' => $this->get('id'),
            'processed' => 1,
            'AND:processed_date_open:>' => $unlock_time,
        ));
        return $result;
    }


    /**
     * Выполнение операция с логирование
     *
     * @param string $action имя метода
     * @param bool $performOpen
     * @return boolean
     */
    public function action($action, $performOpen = true)
    {
        $response = false;
        if ($performOpen) {
            $this->operation('open');
        }
        if (method_exists($this, $action)) {
            $response = $this->$action();
        }
        $this->operation('close');
        return $response;
    }

    /**
     * Операции со статусами
     * @param string $action
     * @param array $entry
     * @return bool
     */
    public function operation($action = 'create', $entry = array())
    {
        $data = array(
            'queue_id' => $this->id,
            'operation' => $action,
            'entry' => $entry
        );
        /* @var bxQueueLog $log */
        $log = $this->xpdo->newObject('bxQueueLog', $data);
        return $log->save();
    }


    /**
     * Get content
     *
     * @return array|boolean $response
     */
    public function content()
    {
        if (!$HandlerQuery = $this->loadSender()->loadHandlerQuery()) {
            return false;
        }

        if (!$HandlerQuery->process($this, false)) {
            return false;
        }
        return $this->get('email_body');
    }

    /**
     * Send message
     *
     * @return array|boolean $response
     */
    private function send()
    {
        if (!$HandlerMailing = $this->loadSender()->loadHandlerMailing()) {
            return false;
        }
        return $HandlerMailing->process($this);
    }


    /**
     * Generate content
     *
     * @return array|boolean $response
     */
    private function query()
    {
        if (!$HandlerQuery = $this->loadSender()->loadHandlerQuery()) {
            return false;
        }
        return $HandlerQuery->process($this);
    }


    /**
     * Проверка исключение подписки
     * Если subscriber_id 0 то проверка проходит без остановки
     * @return bool
     */
    public function isUnSubscribed()
    {
        $id = $this->get('subscriber_id');
        return $id == 0 ? false : (boolean)$this->xpdo->getCount('bxUnSubscribed', array('subscriber_id' => $id));
    }

    /**
     * Проверка исключение подписки
     * @return bool
     */
    public function isUnDeliverable()
    {
        $undeliverable_error_count = (int)$this->xpdo->getOption('bxsender_undeliverable_error_count');
        if ($undeliverable_error_count > 0) {
            $email_to = $this->get('email_to');
            $count = $this->xpdo->getCount('bxUnDeliverable', array('email' => $email_to));
            return $count >= $undeliverable_error_count ? true : false;
        }
        return false;
    }

    /**
     * Удалить сообщение сразуже после отправки
     * @return bool
     */
    public function isDeleteAfterSending()
    {
        return $this->get('delete_after_sending');
    }

    /**
     * Удалить сообщение сразуже после отправки
     * @return bool
     */
    public function isTestingSending()
    {
        return $this->get('testing');
    }
}