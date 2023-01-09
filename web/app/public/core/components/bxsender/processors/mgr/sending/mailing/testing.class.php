<?php
class bxMailingTestingProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {

        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');
        /** @var modProcessorResponse $response */


        $this->modx->lexicon->load('bxsender:mailing');


        $data = array();
        $mailing_id = (int)$this->getProperty('mailing_id');


        $send_user = $this->setCheckbox('send_user');
        if ($send_user) {
            $data[] = $this->modx->user->Profile->get('email');
        }

        $send_emails = trim($this->getProperty('send_emails'));
        if (!empty($send_emails)) {
            $emails = explode(PHP_EOL, $send_emails);
            if (count($emails)) {
                $data = array_merge($data, $emails);
            }
        }

        /* @var bxMailing $Mailing */
        if (!$Mailing = $this->modx->getObject('bxMailing', $mailing_id)) {
            return $this->failure($this->modx->lexicon('bxsender_mailing_testing_err'));
        }

        if (!$Mailing->isServiceBx()) {
            return $this->failure($this->modx->lexicon('bxsender_mailing_testing_err_service', array('service' => $Mailing->get('service'))));
        }

        $emails_sent = array();
        try {

            $delete_after_sending = $this->setCheckbox('delete_after_sending');
            foreach ($data as $email) {
                /* @var bxMailing $Mailing */

                $emails_sent[] = $email;

                /* @var bxQueue $object */
                if ($object = $this->modx->getObject('bxQueue', array(
                    'mailing_id' => $Mailing->get('id'),
                    'email_to' => $email,
                ))) {
                    $object->remove();
                }

                /** @var bxQueue $queue */
                $data = array(
                    'testing' => true,
                    'action' => 'query',
                    'createdon' => time(),
                    'user_id' => $this->modx->user->id,
                    'mailing_id' => $Mailing->get('id'),
                    'subscriber_id' => 0,
                    'delete_after_sending' => $delete_after_sending, // Ставим метку чтобы сообщение автоматически удалилось из очереди в случае успешной отправки
                    'email_to' => $email,
                    'variables' => array(
                        'email_to' => $email,
                        'subscriber_fullname' => $this->modx->user->Profile->get('fullname'),
                        'subscriber_email' => $email
                    ),
                );

                /* @var modProcessorResponse $response */
                $response = $this->modx->runProcessor('queue/create', $data, array(
                    'processors_path' => MODX_CORE_PATH . 'components/bxsender/processors/mgr/'
                ));
                if ($response->isError()) {
                    return $this->failure($response->getMessage() . ". Email {$email}");
                }

                $queue_id = $response->response['object']['id'];


                /* @var bxQueue $queue */
                if ($queue = $this->modx->getObject('bxQueue', $queue_id)) {
                    $queue->action('query');

                    if ($queue = $this->modx->getObject('bxQueue', $queue_id)) {


                        if ($queue->get('state') != 'waiting') {
                            return $this->failure("{$email} находится в состоянии: <b>" . $queue->get('state').'</b> отправка запрещена!');
                        }

                        $queue->action('send');

                        // Проверка сообщения об ошибках
                        if ($object = $this->modx->getObject('bxQueue', $queue_id)) {
                            if ($object->get('state') == 'error') {
                                $msg = $object->get('service_message');
                                return $this->failure(". Email {$email} testing msg:" . $msg['error']);
                            }

                        }
                    }
                }
                $this->modx->error->reset();
            }


        } catch (ExceptionSending $e) {
            return $this->failure($e->getMessage());
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }


        $str = implode('<br>', $emails_sent);
        $message = $this->modx->lexicon('bxsender_mailing_testing', array('email' => $str));
        return $this->success($message);
    }
}

return 'bxMailingTestingProcessor';