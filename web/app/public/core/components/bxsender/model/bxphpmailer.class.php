<?php
if (!class_exists('modPHPMailer')) {
    include_once MODX_CORE_PATH . 'model/modx/mail/modphpmailer.class.php';
}

class bxPHPMailer extends modPHPMailer
{

    /**
     * Send the email, applying any attributes to the mailer before sending.
     *
     * @param array $attributes An array of attributes to pass when sending
     * @return boolean True if the email was successfully sent
     */
    public function send(array $attributes = array())
    {
        /* @var PHPMailer $mailer */
        $mailer = $this->mailer;


        $connect = true;
        $headers = $mailer->getCustomHeaders();
        if (!empty($headers)) {
            foreach ($headers as $header) {
                if (isset($header[0])) {
                    if ($header[0] == 'bxSendex-hash') {
                        $connect = false;
                        break;
                    }
                }
            }
        }

        if ($connect) {
            $service = null;
            $options = array();

            /* @var bxSender $bxSender */
            $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');


            // if a word is found in the outgoing address
            $all_messages_system = $this->modx->getOption('bxsender_all_messages_system', null, false);
            if (strripos($mailer->From, 'bxsender') !== false) {
                list($service, $name) = explode('@', $mailer->From);
            } else if ($all_messages_system) {
                $service = 'modx';
            }

            if ($data = $this->changeStatusMinishop2($bxSender)) {
                $service = $data['service'];
                $options = $data['options'];
            }

            if ($service) {
                $this->isBxTransport = true;
                $response = $bxSender->transitMessage($service, $mailer->getToAddresses(), $mailer->Subject, $mailer->Body, array(), $options);
                $this->isBxTransport = false;
                return $response;
            }
        }

        // Запрещаем слать сообщения, чтобы они оставались в очереди
        $bxsender_do_not_send_messages = $this->modx->getOption('bxsender_do_not_send_messages', null, false);
        if ($bxsender_do_not_send_messages) {
            return true;
        }
        return parent::send($attributes);
    }

    /**
     * Смена статуса заказа в minishop
     * @param bxSender $bxSender
     * @return array|bool
     */
    private static function changeStatusMinishop2(bxSender $bxSender)
    {
        // Если включено отправка сообщений при смене статуса закзаа то проверяем запись заказа
        $minishop2_status_change = $bxSender->modx->getOption('bxsender_minishop2_status_change', null, false);
        if ($minishop2_status_change and $order = $bxSender->getOrder()) {
            $service_minishop2 = $bxSender->modx->getOption('bxsender_minishop2_prefix_service', null, 'minishop2');
            if (!empty($service_minishop2)) {
                $service = $service_minishop2;
                // Проверям что сервис отправки выбран minishop2 и есть заказ
                if ($order) {
                    $options = array();
                    $from = null;
                    if ($order['email_manager']) {
                        // Сперва отправляем сообщение менеджеру
                        $from = 'manager';
                        $bxSender->orderSendManager();
                    } else if ($order['email_user']) {
                        // Затем пользователю
                        $from = 'user';
                        $bxSender->orderSendUser();
                    }
                    if ($from) {
                        $options = array(
                            'order_id' => $order['order_id'],
                            'status' => $order['status'],
                            'from' => $from,
                        );
                    }
                    return array(
                        'service' => $service,
                        'options' => $options,
                    );
                }
            }
        }
        return false;
    }
}