<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 31.05.2019
 * Time: 20:48
 */

class bxSenderControllerManager extends bxSenderControllerDefault
{
    /**
     * Default config
     *
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $this->config = array_merge(array(
            'bx_action' => 'manager',
            'tplForm' => 'tpl.bxSender.Form.Manager',
        ), $config);
    }

    public function initialize()
    {
        if (!isset($_SESSION['bxSenderHash'])) {
            $_SESSION['bxSenderHash'] = array();
        }
        return parent::initialize();
    }

    public function regJsScriptAfter()
    {
        $js = trim($this->modx->getOption('bxsender_frontend_js'));
        if (!empty($js) && preg_match('#\.js#i', $js)) {
            $this->modx->regClientScript($js);
        }
    }


    /** @inheritdoc} */
    public function getForm()
    {
        if (!$data = $this->bx->getToken()) {
            return $this->modx->lexicon('bxsender_fn_manager_err_access_closed');
        }

        /*
            if (isset($data['subscribe_id']) and $this->modx->getAuthenticatedUser()) {
                $subscribe_id = $data['subscribe_id'];
                $criteria = array(
                 'user_id' => $this->modx->user->get('id'),
                 'id' => $subscribe_id
                );
            }
        */

        $token = $data['token'];
        $email = $data['email'];
        if (empty($token)) {
            return $this->modx->lexicon('bxsender_fn_manager_err_token');
        }

        if (empty($email)) {
            return $this->modx->lexicon('bxsender_fn_manager_err_token');
        }

        $this->confirmEmail($token, true, 'restore'); // Уничтожаем сообщение, иначе повторно невозможно будет отправить письмо для восстановления пароля
        $criteria = array('token' => $token, 'email' => $email);

        /* @var bxSubscriber $subscribe */
        if (!$criteria or !$subscribe = $this->modx->getObject('bxSubscriber', $criteria)) {
            return $this->modx->lexicon('bxsender_fn_manager_err_not_found_subscribe');
        }

        if ($subscribe->get('sent_confirmation') and !$subscribe->get('confirmed')) {
            return $this->modx->lexicon('bxsender_fn_manager_err_confirm_subscribe_email');
        }

        $segments = $subscribe->getSubscriptionsSegment();
        if (count($segments) == 0) {
            return $this->modx->lexicon('bxsender_fn_manager_err_no_action_segment');
        }

        // Подписан на сегменты
        $segments_ids = array();
        foreach ($segments as $key => $segment) {
            if ($segment['checked']) {
                $segments_ids[] = $segment['id'];
            }
        }

        $data = array(
            'state' => $subscribe->get('state'),
            'email' => $subscribe->get('email'),
            'fullname' => $subscribe->get('fullname'),
            'segments' => implode(',', $segments_ids),
            'token' => $subscribe->get('token')
        );
        return $this->getAjaxForm($data);
    }

    /**
     * @param array $data
     * @return array|string
     */
    public function hook(array $data = array())
    {
        if (!$criteria = $this->bx->getToken($this->bx->setToken($data))) {
            return $this->modx->lexicon('bxsender_fn_manager_err_access_closed');
        }

        $segments = !empty($data['segments']) ? (array)$data['segments'] : false;
        if ($segments) {
            $segments = array_map('intval', $segments);
        }

        $state = !empty($data['state']) ? $data['state'] : false;
        $fullname = $this->vFullname($data['fullname']) ? $data['fullname'] : '';


        if (!$this->vFullname($data['fullname'])) {
            $this->setError('fullname', 'bxsender_fn_manager_err_fullname');
        }

        if ($state != 'subscribe' and $state != 'unsubscribed') {
            $this->setError('state', 'bxsender_fn_manager_err_status');
        }

        if (empty($segments) and $state == 'subscribe') {
            $this->setError('segments', 'bxsender_fn_manager_err_change_segment');
        }


        if (count($this->errors)) {
            return $this->error('bxsender_fn_manager_err_msg', $this->errors);
        }


        /* @var bxSubscriber $subscribe */
        if (!$subscribe = $this->modx->getObject('bxSubscriber', $criteria)) {
            return $this->error('bxsender_fn_manager_err_sub_not_found');
        } else {
            $subscribe->set('fullname', $fullname);
            // Привязывает пользователя к подписке если он авторизован
            if (isset($data['bind_user_subscription']) and $this->modx->user->isAuthenticated()) {
                $bind_user_subscription = (boolean)$data['bind_user_subscription'];
                if ($bind_user_subscription) {
                    $subscribe->set('user_id', $this->modx->user->id);
                }
            }

            $segments = array_flip($segments);
            foreach ($segments as $k => $v) {
                $segments[$k] = 1;
            }
            if (!$subscribe->updateSegments($state, $segments)) {
                return $this->error('bxsender_fn_manager_err_save');
            } else {
                if ($state == 'subscribe') {
                    $message = 'bxsender_fn_manager_msg_success';
                } else {
                    $message = 'bxsender_fn_manager_msg_success_unsubscribe';
                }
            }
        }
        return $this->success($message);
    }
}

return 'bxSenderControllerManager';