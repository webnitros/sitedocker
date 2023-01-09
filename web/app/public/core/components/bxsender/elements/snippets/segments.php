<?php
/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';

$bxSender->loadPdoTools();

$tpl = $modx->getOption('tpl', $scriptProperties, 'bxSegments');
$checkeds = $modx->getOption('checkeds', $scriptProperties, '');
if (!empty($checkeds)) {
    $checkeds = explode(',', $checkeds);
} else {
    $checkeds = array();
}


$data = array();
$segments = array();

/* @var bxSegment $object */
$q = $modx->newQuery('bxSegment');
$q->where(array(
    'active' => 1,
    'allow_subscription' => 1
));
$q->sortby('rank', 'ASC');
if ($objectList = $modx->getCollection('bxSegment', $q)) {
    foreach ($objectList as $object) {
        $row = $object->toArray();

        $row['checked'] = in_array($row['id'], $checkeds) ? 'checked' : '';
        $segments[] = $row;
    }
}

$data['segments'] = $segments;
$outer = $bxSender->pdoFetch->getChunk($tpl, $data);
return $outer;