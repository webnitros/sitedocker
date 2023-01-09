<?php
/** @var AjaxForm $AjaxForm */
/** @var array $scriptProperties */

/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';
$bxSender->loadController('restore');
return $bxSender->loadAction('restore/getForm', $scriptProperties);