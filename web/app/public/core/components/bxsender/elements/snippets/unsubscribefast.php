<?php
/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';

$bxSender->loadPdoTools();
$bxSender->loadLexicon('frontend');
$tpl = $modx->getOption('tpl', $scriptProperties, 'bxUnSubscribe');
$mode_unsubscribe = $modx->getOption('mode_unsubscribe', $scriptProperties, 'email');


$subscribe_manager_page = '';
$subscriber_id = false;
$message = '';
$response = false;
if (!$data = $bxSender->getHashData()) {
    return $modx->lexicon('bxsender_fn_unsubscribe_err_hash');
} else {
    if ($data['queue_id']) {
        $subscriber_id = !empty($data['subscriber_id']) ? (int)$data['subscriber_id'] : false;
        $queue_id = $data['queue_id'];
        $email = '';
        if (!empty($data['email'])) {
            $email = $data['email'];
        } else {
            if ($Queue = $modx->getObject('bxQueue', $queue_id)) {
                $email = $Queue->get('email_to');
            }
        }

        /* @var bxStatUnSubscribed $UnSubscribed */
        if (!$UnSubscribed = $modx->getObject('bxStatUnSubscribed', array('queue_id' => $queue_id))) {
            $UnSubscribed = $modx->newObject('bxStatUnSubscribed');
            $UnSubscribed->fromArray($data);
        }
        $response = $UnSubscribed->save();
        $UnSubscribed->unSubscriber($subscriber_id, $email);
    }
    if ($response) {
        if ($subscriber_id) {
            /* @var bxSubscriber $Subscriber */
            if ($Subscriber = $modx->getObject('bxSubscriber', $subscriber_id)) {
                $subscribe_manager_page = $bxSender->getPageSubscribeManager($Subscriber->getTokenData());
            }
        }
    }
}
return $bxSender->pdoFetch->getChunk($tpl, array(
    'subscribe_manager_page' => $subscribe_manager_page,
));