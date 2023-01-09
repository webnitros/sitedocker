<?php
define('MODX_API_MODE', true);
define('BXSENDER_CRON', true);
ignore_user_abort(true);
set_time_limit(0);

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_BASE_PATH . 'index.php';

$corePath = $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/');
require_once $corePath . 'cron/send.php';