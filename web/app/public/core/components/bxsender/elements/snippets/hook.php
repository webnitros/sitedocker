<?php
/** @var array $scriptProperties */
/** @var AjaxForm $AjaxForm */
/** @var bxSender $bxSender */

$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';


if (isset($scriptProperties['AjaxForm'])) {
    unset($scriptProperties['AjaxForm']);
}


$action = false;
if (!empty($_REQUEST['bx_action'])) {
    $action = $_REQUEST['bx_action'];
} else if (!empty($scriptProperties['bx_action'])) {
    $action = $scriptProperties['bx_action'];
} else {
    // Возвращаем пусто чтобы продолжить запуск ajaxForm
    return '';
}


$bxSender->config['json_response'] = true;

if (!$action) {
    return $bxSender->error('controller for hook is not specified POST or GET "bxaction"');
}
$data = $scriptProperties['fields'];
if (isset($data['bx_action'])) {
    unset($data['bx_action']);
}
$response = $bxSender->loadAction($action . '/hook', $scriptProperties, $data);
if (is_string($response)) {
    return $bxSender->error($response);
}
if (!$response['success']) {
    return $bxSender->error($response['message'], $response['data']);
}
return $bxSender->success($response['message']);