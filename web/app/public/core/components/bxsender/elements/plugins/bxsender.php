<?php
/* @var bxSender $bxSender */
/* @var array $scriptProperties */
switch ($modx->event->name) {
    case "OnMODXInit":
        $modx->getService('mail', 'bxPHPMailer', MODX_CORE_PATH . 'components/bxsender/model/');
        break;
    case 'OnHandleRequest':
        if ($modx->context->key == 'mgr') {
            return '';
        }
        /* @var bxSender $bxSender */
        $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        if ($data = $bxSender->getHashData($hash)) {
            if ($data['queue_id']) {
                $count = 0;
                /* @var bxStatClicks $Clicks = */
                if (!$Clicks = $modx->getObject('bxStatClicks', array('queue_id' => $data['queue_id']))) {
                    $Clicks = $modx->newObject('bxStatClicks');
                    $Clicks->fromArray($data);
                } else {
                    $count = $Clicks->get('count');
                }
                $count++;
                $Clicks->set('count', $count);
                $Clicks->save();
            }
        }
        break;
    case 'OnUserBeforeSave':
        /* @var modUser $user */
        /* @var string $mode */
        $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $bxSender->createUserSubscribe($user, $mode);
        break;
    case 'OnUserActivate':
        /* @var modUser $user */
        /* @var string $mode */
        $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $bxSender->createUserSubscribeActivate($user, $mode);
        break;
    case 'msOnGetOrderCustomer':
        /* @var modUser $customer */
        /* @var string $order */
        $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $response = $bxSender->createUserSubscribeOrderCreate($customer, $order);

        if (!$response['success']) {
            $message = $response['message'];
            if (isset($response['data']) and count($response['data']) > 0) {
                $message = array_shift($response['data']);
            }
            $modx->event->output($message);
            return $message;
        }
        break;
    case 'msOnChangeOrderStatus':
        /* @var msOrder $order */

        // Включаем пересылку через bxSender
        $minishop2_status_change = $modx->getOption('bxsender_minishop2_status_change', null, false);
        if ($minishop2_status_change) {
            $order = $scriptProperties['order'];
            $status = $scriptProperties['status'];

            /* @var msOrderStatus $OrderStatus */
            if (is_int($status) and $status != 0) {
                if ($OrderStatus = $modx->getObject('msOrderStatus', $status)) {
                    /* @var string $order */
                    $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
                    $bxSender->setOrder($order->get('id'), $status, $OrderStatus->get('email_user'), $OrderStatus->get('email_manager'));
                }
            }
        }
        break;
    case 'msOnManagerCustomCssJs':

        $controller = $scriptProperties['controller'];
        if (is_object($controller)) {
            if ($controller->config['controller'] == "mgr/orders" && $controller->config['namespace'] == "minishop2") {
                $bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
                $controller->addLastJavascript($bxSender->config['jsUrl'] . 'mgr/widgets/minishop/order.js');
                $controller->addLastJavascript($bxSender->config['jsUrl'] . 'mgr/widgets/minishop/orders.grid.mails.js');
                $controller->addHtml('<script type="text/javascript">
                    bxSender = [];
                    bxSender.config = ' . json_encode($bxSender->config) . ';
                    bxSender.config.connector_url = "' . $bxSender->config['connectorUrl'] . '";
                </script>');
                }

        }
        break;

}