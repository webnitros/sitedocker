<?php

/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 31.05.2019
 * Time: 20:48
 */
class bxSenderControllerSubscribe extends bxSenderControllerDefault
{
    /**
     * Default config
     *
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $this->config = array_merge(array(
            'bx_action' => 'subscribe',
            'tplForm' => 'tpl.bxSender.Form.Subscribe',
            'tplEmail' => 'tpl.bxSender.Email.Activation',
            'user_id' => 0,
        ), $config);
    }

    /** @inheritdoc} */
    public function getForm()
    {
        $ManagerSubscribe = $this->bx->getPageSubscribeManager();
        $this->modx->setPlaceholder('bx_manager_subscribe_page', $ManagerSubscribe);
        return $this->getAjaxForm();
    }


    /** @inheritdoc} */
    public function confirmationEmail()
    {
        if (empty($_GET['hash'])) {
            return $this->modx->lexicon('bxsender_fn_subscribe_confirmation_err_hash_empty');
        }

        if (empty($_GET['email'])) {
            return $this->modx->lexicon('bxsender_fn_subscribe_confirmation_err_email_empty');
        }

        $email = $_GET['email'];
        $hash = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['hash']);
        if (strlen($hash) != 40) {
            return $this->modx->lexicon('bxsender_fn_subscribe_confirmation_err_hash_valid_get');
        }

        if (!$Subscriber = $this->modx->getObject('bxSubscriber', array('hash_activate_subscription' => $hash))) {
            return $this->modx->lexicon('bxsender_fn_confirmationemail_err');
        }

        $message = null;
        $response = $this->confirmEmail($hash);
        if ($response) {
            $message = 'bxsender_fn_confirmationemail_subscribe_success';
        }
        if (!$message) {
            /* @var bxSubscriber $Subscriber */
            if ($Subscriber) {

                if ($email != $Subscriber->get('email')) {
                    return $this->modx->lexicon('bxsender_fn_subscribe_confirmation_err_hash_valid');
                }

                if ($Subscriber->get('state') == 'subscribe') {
                    $message = 'bxsender_fn_confirmationemail_is_subscribe_success';
                } else {
                    $message = 'bxsender_fn_confirmationemail_is_not_subscribe_success';
                }
            }
        }

        if (!$message) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('bxsender_fn_confirmationemail_err_log', array('hash' => $hash)), '', __METHOD__, __FILE__, __LINE__);
            return $this->modx->lexicon('bxsender_fn_confirmationemail_err');
        }

        $link = $this->bx->getPageSubscribeManager($Subscriber->getTokenData());

        return $this->getParser($this->modx->lexicon($message, array('link' => $link)));

    }


    /**
     * @param array $data
     * @return array
     */
    public function hook(array $data = array())
    {

        $fullname = $data['fullname'];
        if (!$this->vFullname($fullname)) {
            $this->errors['fullname'] = $this->modx->lexicon('bxsender_fn_subscribe_err_name_empty');
        }


        $email = $data['email'];
        if (empty($email)) {
            $this->errors['email'] = $this->modx->lexicon('bxsender_fn_subscribe_err_email_empty');
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/.+@.+\..+/i', $email)) {
            $this->errors['email'] = $this->modx->lexicon('bxsender_fn_subscribe_err_email');
        } else if ($Subscriber = $this->modx->getObject('bxSubscriber', array('email' => $email))) {
            if ($Subscriber->get('state') != 'activate_subscription') {
                $this->errors['email'] = $this->modx->lexicon('bxsender_fn_subscribe_err_is_subscribe');
            }
        }

        $segments = $data['segments'];
        if (empty($segments)) {
            $this->errors['segments'] = $this->modx->lexicon('bxsender_fn_subscribe_err_segments_empty');
        }


        if (count($this->errors) > 0) {
            return $this->error('bxsender_fn_subscribe_err_message', $this->errors);
        }

        // Если пользователь подписан то говорим ему перейдите на страницу управление подписками
        $hash_activate_subscription = null;


        /* @var bxSubscriber $Subscriber */
        if ($Subscriber = $this->modx->getObject('bxSubscriber', array('email' => $email))) {
            // Письмо уже отправлено и подписчик находится в состоянии активации подписки
            $hash_activate_subscription = $Subscriber->get('hash_activate_subscription');
            $response = $this->confirmEmail($hash_activate_subscription, false);
            if ($response) {
                $minutes = $this->bx->config['linkTTL'] / 60;
                return $this->error($this->modx->lexicon('bxsender_fn_subscribe_err_next_slots', array('minutes' => $minutes)));
            } else {
                // Генерируем новый код
                $code = $this->activationEmailHash($email);
                $Subscriber->set('hash_activate_subscription', $code);
                $Subscriber->save();
                $hash_activate_subscription = $Subscriber->get('hash_activate_subscription');
            }
        } else {
            /* @var modProcessorResponse $response */
            $user_id = 0;
            if (!empty($this->config['user_id'])) {
                if ($object = $this->modx->getObject('modUser', $this->config['user_id'])) {
                    $user_id = $this->config['user_id'];
                }
            } else {
                $user_id = $this->modx->user->isAuthenticated() ? $this->modx->user->get('id') : 0;
            }

            $data = array(
                'user_id' => $user_id,
                'email' => $email,
                'fullname' => $fullname,
                'segments' => $segments,
                'state' => 'activate_subscription',
                'sent_confirmation' => 1,
                'hash_activate_subscription' => $this->activationEmailHash($email)
            );

            $response = $this->bx->runProcessor('mgr/subscription/subscriber/create', $data);
            if ($response->isError()) {
                return $this->error($response->getMessage(), $response->getAllErrors());
            }
            $hash_activate_subscription = $response->response['object']['hash_activate_subscription'];
        }


        if (empty($hash_activate_subscription)) {
            return $this->error('bxsender_fn_subscribe_err_get_hash');
        }

        // Отправка сообщения с письмом для активации E-mail адреса
        $link = $this->bx->getAction('confirmationemail', false, array('hash' => $hash_activate_subscription, 'email' => $email));

        // Отправка сообщения на E-mail
        $response = $this->sendEmail($email, $this->config['tplEmail'], array(
            'link' => $link,
            'email' => $email,
        ));
        if (!$response) {
            // Удаляем из регистрац так как невозможно будет попробовать создать еще одну запись
            $this->confirmEmail($hash_activate_subscription, true, 'subscribe', true);
            return $this->error($this->modx->lexicon('bxsender_fn_subscribe_err_send_message', array('email' => $email)));
        }
        return $this->success($this->modx->lexicon('bxsender_fn_subscribe_success'));
    }


    /**
     * Активация пользователя после подтверждения E-mail адреса
     * @param array $data
     * @return array
     */
    public function userActivation(array $data = array())
    {
        $email = $data['email'];

        /* @var bxSubscriber $Subscriber */
        if ($Subscriber = $this->modx->getObject('bxSubscriber', array('email' => $email))) {
            // Если происходила регистрация нового пользоватля и он подтвердил свой E-mail адрес, существующие подписки присваеваются этому пользователю
            $Subscriber->set('user_id', $data['user_id']);
            $Subscriber->set('fullname', $data['fullname']);
            $Subscriber->set('confirmed', 1);

            // Обновляем сегменты если пользователь установил новые
            $state = 'unsubscribed';
            $segments = array();
            if (!empty($data['segments'])) {
                $segments = is_array($data['segments']) ? $data['segments'] : explode(',', $data['segments']);
                $segments = array_filter($segments);
            }
            if (!empty($segments)) {
                $state = 'subscribe';
            }

            $Subscriber->updateSegments($state, $segments);
            if (!$Subscriber->save()) {
                return $this->error('bxsender_fn_subscribe_err_save');
            }
        } else {
            $data = array_merge($data, array('confirmed' => 1));
            /* @var modProcessorResponse $response */
            $response = $this->bx->runProcessor('mgr/subscription/subscriber/create', $data);
            if ($response->isError()) {
                return $this->error($response->getMessage(), $response->getAllErrors());
            }
        }
        return $this->success('bxsender_subscribe_create');
    }

}

return 'bxSenderControllerSubscribe';