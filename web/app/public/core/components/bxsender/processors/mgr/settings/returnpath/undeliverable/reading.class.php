<?php

/**
 * Get an Item
 */
class bxUnDeliverableReadingProcessor extends modProcessor
{
    /* @var bxReturnPath $returnPath */
    public $returnPath;

    /* @var bxSender $bxSender */
    public $bxSender;

    /* @var string $path */
    public $path;


    public function initialize()
    {

        /* @var bxSender $bxSender */
        $this->bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $this->bxSender->loadAutoLoad();
        if (!$this->returnPath = $this->bxSender->loadReturnPath()) {
            return 'Error load Class bxReturnPath';
        }

        if (!class_exists('Cws\CwsDebug')) {
            return 'Error load class';
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

        $email_from = $this->returnPath->get('email');
        $response = true;
        $total_message_read = 0;
        try {
            $cwsDebug = new Cws\CwsDebug();
            $cwsMbh = new Cws\MailBounceHandler\Handler($cwsDebug);
            $cwsMbh->setNeutralProcessMode(); // default


            $path = $this->returnPath->getPatch();
            if ($cwsMbh->openEmlFolder($path) !== false) {

                if ($cwsMbh->isFileExists()) {
                    // process mails!
                    $result = $cwsMbh->processMails();
                    if (!$result instanceof \Cws\MailBounceHandler\Models\Result) {
                        $response = $cwsMbh->getError();
                    } else {
                        $mails = $result->getMails();
                        foreach ($mails as $mail) {
                            if (!$mail instanceof \Cws\MailBounceHandler\Models\Mail) {
                                continue;
                            }


                            $token = $mail->getToken();
                            $type = $mail->getType();
                            if ($type == 'bounce') {
                                $save_token = str_ireplace('.eml', '', $token);


                                $hash = $mail->getHashQueue();

                                if ($data = $this->bxSender->getHashData($hash)) {


                                    $queue_id = $data['queue_id'];
                                    $subject = trim(mb_decode_mimeheader($mail->getSubject()));
                                    
                                    $main = array_merge($data, array(
                                        'token' => $save_token,
                                        'subject' => $subject,
                                    ));

                                    foreach ($mail->getRecipients() as $recipient) {
                                        if (!$recipient instanceof \Cws\MailBounceHandler\Models\Recipient) {
                                            continue;
                                        }
                                        // Сравниваем чтобы емаил не совпадал с емайлом отправителя
                                        $email_to = $recipient->getEmail();
                                        if ($email_from != $email_to) {
                                            $recipient = array_merge($main, array(
                                                'cat' => $recipient->getBounceCat(),
                                                'type' => $recipient->getBounceType(),
                                                'action' => $recipient->getAction(),
                                                'status' => $recipient->getStatus(),
                                                'email' => $recipient->getEmail(),
                                            ));


                                            if (!empty($queue_id)) {
                                                // Записываем статистику по получению отскока для этого письма
                                                $count = 0;
                                                /* @var bxStatUnDeliverable $StatUnDeliverable = */
                                                if (!$StatUnDeliverable = $this->modx->getObject('bxStatUnDeliverable', array('queue_id' => $queue_id))) {
                                                    $StatUnDeliverable = $this->modx->newObject('bxStatUnDeliverable');
                                                    $StatUnDeliverable->fromArray($data);
                                                } else {
                                                    $count = $StatUnDeliverable->get('count');
                                                }
                                                $count++;
                                                $StatUnDeliverable->set('count', $count);
                                                $StatUnDeliverable->save();
                                            }

                                            // Ищим по токену сообщения и соединению
                                            if (!$count = (boolean)$this->modx->getCount('bxUnDeliverable', array(
                                                'token' => $save_token,
                                            ))) {
                                                // Создаем запись о ошибке доставки
                                                /* @var bxUnDeliverable $bxUnDeliverable */
                                                $UnDeliverable = $this->modx->newObject('bxUnDeliverable');
                                                $UnDeliverable->fromArray($recipient);
                                                if (!$UnDeliverable->save()) {
                                                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Error save bxUnDeliverable" . print_r($recipient, 1), '', __METHOD__, __FILE__, __LINE__);
                                                }

                                            }

                                        }
                                    }
                                }

                            }
                            // Устанавливаем метку о прочтении сообщения
                            $this->returnPath->read($token);
                            $total_message_read++;
                        }
                    }
                }
            } else {
                $response = $cwsMbh->getError();
            }

        } catch (Exception $e) {
            $response = $e->getMessage();
        }


        if ($response !== true) {
            $message = "Error reading, message: " . $response;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($message);
        }
        return $this->success('', array(
            'total_message_read' => $total_message_read
        ));
    }

}

return 'bxUnDeliverableReadingProcessor';