<?php

/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 31.05.2019
 * Time: 20:48
 */
class bxSenderControllerRestore extends bxSenderControllerDefault
{
    /**
     * Default config
     *
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $this->config = array_merge(array(
            'bx_action' => 'restore',
            'tplForm' => 'tpl.bxSender.Form.Restore',
            'tplEmail' => 'tpl.bxSender.Email.Restore',
        ), $config);
    }

    /** @inheritdoc} */
    public function getForm()
    {
        return $this->getAjaxForm();
    }

    /** @inheritdoc} */
    public function hook(array $data = array())
    {
        if (empty($data['email'])) {
            $this->setError('email', 'bxsender_fn_subscribe_err_email_empty');
        }

        $email = $data['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/.+@.+\..+/i', $email)) {
            $this->setError('email', 'bxsender_fn_subscribe_err_email');
        }

        if ($this->isError()) {
            return $this->error('bxsender_fn_subscribe_restore_error_messege', $this->getErrors());
        }

        /* @var bxSubscriber $subscribe */
        if (!$subscribe = $this->modx->getObject('bxSubscriber', array('email' => $email))) {
            return $this->error('bxsender_fn_subscribe_restore_error_messege', array('email' => $this->modx->lexicon('bxsender_fn_subscribe_restore_err_email')));
        }

        $token = $subscribe->get('token');
        $topic = 'restore';
        if ($this->confirmEmail($token, false, $topic)) {
            $minutes = $this->bx->config['linkTTL'] / 60;
            return $this->error($this->modx->lexicon('bxsender_fn_subscribe_restore_err_next_slots', array('minutes' => $minutes)));
        }

        // Записывае для временного хранения
        $this->activationEmailHash($email, $topic, $token);
        $link = $this->bx->getPageSubscribeManager(array(
            'email' => $email,
            'token' => $token,
        ));
        $response = $this->sendEmail($email,$this->config['tplEmail'], array('link' => $link, 'email' => $email));
        if (!$response) {
            return $this->error($this->modx->lexicon('bxsender_fn_subscribe_err_send_message', array('email' => $email)));
        }
        return $this->success('bxsender_fn_subscribe_restore_success');
    }
}

return 'bxSenderControllerRestore';