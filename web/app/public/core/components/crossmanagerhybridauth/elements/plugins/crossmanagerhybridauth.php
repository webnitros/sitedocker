<?php
/** @var modX $modx */
/* @var array $scriptProperties */
switch ($modx->event->name) {
    case 'OnHandleRequest':
    case 'OnWebAuthentication':
    case 'OnManagerLoginFormPrerender':
        /* @var crossManagerHybridauth $crossManagerHybridauth*/
        $crossManagerHybridauth = $modx->getService('crossmanagerhybridauth', 'crossManagerHybridauth', $modx->getOption('crossmanagerhybridauth_core_path', $scriptProperties, $modx->getOption('core_path') . 'components/crossmanagerhybridauth/') . 'model/');
        if ($crossManagerHybridauth instanceof crossManagerHybridauth) {
            $crossManagerHybridauth->loadHandlerEvent($modx->event, $scriptProperties);
        }
        break;
}
return '';