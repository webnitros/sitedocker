<?php
$image = imagecreatetruecolor(rand(2, 10), rand(2, 10))
or die('Cannot create image');
imagefill($image, 0, 0, 0xFFFFFF);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';


/* @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
if ($data = $bxSender->getHashData($hash, false, 4)) {
    if ($data['queue_id']) {
        /* @var bxStatOpens $Opens */
        if (!$count = (boolean)$modx->getCount('bxStatOpens', array('queue_id' => $data['queue_id']))) {
            $Opens = $modx->newObject('bxStatOpens');
            $Opens->fromArray($data);
            $Opens->save();
        }
    }
}
