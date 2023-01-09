<?php

interface bxMailingInterface
{
    /**
     * Send message queue to service
     *
     * @return array|boolean $response
     */
    public function initialize();

    /**
     * Send message queue to service
     * @param bxQueue $queue
     * @return boolean $response
     */
    public function process(bxQueue $queue);

    /**
     * Return content message queue
     *
     * @return boolean $response
     */
    public function content();

}


class bxMailingHandler implements bxMailingInterface
{

    /* @var bxSender $bx */
    public $bx;

    /* @var bxQueue $queue */
    protected $queue;

    /* @var modPHPMailer $mail */
    protected $mail;

    /* @var PHPMailer $mailer */
    protected $mailer;

    /* @var bxMailSender|null $MailSender */
    protected $MailSender = null;

    /* @var bxReturnPath|null $ReturnPath */
    protected $ReturnPath = null;

    /**
     * @param bxSender $bx
     */
    function __construct(bxSender & $bx)
    {
        $this->bx = $bx;
    }

    /**
     * Инициализация процессов
     * @return bool
     */
    public function initialize()
    {
        /* @var modPHPMailer $mail */
        $mail = $this->bx->modx->getService('mail', 'mail.modPHPMailer');
        $this->mail = $mail;
        $this->mailer = $mail->mailer;
        if (!$this->MailSender = $this->bx->loadMailSender()) {
            $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error load class bxMailSender", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        if (!$this->ReturnPath = $this->bx->loadReturnPath()) {
            $this->bx->modx->log(modX::LOG_LEVEL_ERROR, "Error load class bxReturnPath", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        return true;
    }

    /** @inheritdoc} */
    public function process(bxQueue $queue)
    {
        $this->queue = &$queue;

        // Повторная установка транспортника чтобы письмо сбрасывалось правильно
        $this->MailSender->transporter($this->mail);

        // add message content
        $this->mail->address('to', $this->email());
        $this->mail->set(modMail::MAIL_SUBJECT, $this->subject());
        $this->mail->set(modMail::MAIL_BODY, $this->content());
        $this->mail->set(modMail::MAIL_BODY_TEXT, $this->text());
        $this->mail->setHTML(true);

        $this->mailer->addCustomHeader('Content-Type', 'text/plain');

        // add headher Message
        if ($headers = $this->addHeader()) {
            foreach ($headers as $key => $value) {
                $this->mailer->addCustomHeader($key, $value);
            }
        }

        $response = $this->beforeSendMessage();
        if ($response !== true) {
            $state = 'error';
            $messageError = $response;
        } else {
            $messageError = null;
            if (!$this->mail->send()) {
                $state = 'error';
                $messageError = $this->mailer->ErrorInfo;
                if (!$this->queue->isTestingSending()) {
                    $this->afterSendMessage($state, $messageError);
                    $this->MailSender->processError('send', $messageError, null, true);
                    // Делаем паузу если не смогли сразу отправить сообщение
                    usleep(500000);
                    return true;
                }
            } else {
                $state = 'sent';
            }

            // reset error
            $this->mail->reset();
        }

        $this->afterSendMessage($state, $messageError);
        //sleep(1);
        return true;
    }

    /**
     * Метод запускается после отправки сообщения получателю
     */
    public function afterSendMessage($state, $messageError)
    {
        if ($this->queue->isDeleteAfterSending() and $state == 'sent') {
            $this->queue->remove();
        } else {
            $this->queue->operation('update', array(
                'state' => $state,
                'completed' => 1,
                'reject_reason' => $state,
                'service_message' => array('error' => $messageError),
            ));
        }

        if (!$this->queue->isTestingSending()) {
            // Обновление счетчика отправленных писем
            $this->MailSender->incrementSentCount();
        }
    }

    /**
     * Добавление заголовков в шапку
     */
    public function addHeader()
    {
        // Return-path
        if ($this->ReturnPath and $this->ReturnPath->isEnable()) {
            if (!$this->MailSender->isSMTP() OR ($this->MailSender->isSMTP() and $this->ReturnPath->isEnableEMail($this->MailSender->get('from')))) {
                $this->mail->set(modMail::MAIL_SENDER, $this->ReturnPath->get('email'));
            }
        }

        $data['bxSendex-hash'] = "<{$this->queue->getHash()}>";
        $url = $this->bx->getAction('unsubscribe', $this->queue->getHash());
        $data['List-Unsubscribe'] = "<{$url}>";
        return $data;
    }

    /**
     * @return bool
     */
    public function beforeSendMessage()
    {
        return true;
    }


    /** @inheritdoc} */
    public function email()
    {
        return $this->queue->get('email_to');
    }

    /** @inheritdoc} */
    public function content()
    {
        return $this->queue->getContentHtml();
    }


    /** @inheritdoc} */
    public function text()
    {
        return $this->queue->get('email_body_text');
    }

    /** @inheritdoc} */
    public function subject()
    {
        return $this->queue->get('email_subject');
    }
}