<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';

/** @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
if ($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoFetch = new $pdoClass($modx, $scriptProperties);
} else {
    return false;
}
$pdoFetch->addTime('pdoTools loaded.');

$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.bxSender.Subscriptions.row');
$user_id = $modx->getOption('user_id', $scriptProperties, 0);

if (empty($user_id)) {
    $user_id = $modx->user->id;
}

$class = 'bxSubscriber';
$where = array(
    'bxSubscriber.user_id' => $user_id,
);

// Join tables
$leftJoin = array();
$select = array(
    'bxSubscriber' => $modx->getSelectColumns('bxSubscriber', 'bxSubscriber'),
);

// Include linked products
$innerJoin = array();

// Add user parameters
foreach (array('where', 'leftJoin', 'innerJoin', 'select', 'groupby') as $v) {
    if (!empty($scriptProperties[$v])) {
        $tmp = $scriptProperties[$v];
        if (!is_array($tmp)) {
            $tmp = json_decode($tmp, true);
        }
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
    }
    unset($scriptProperties[$v]);
}
$pdoFetch->addTime('Conditions prepared');


$default = array(
    'class' => 'bxSubscriber',
    'where' => $where,
    'leftJoin' => $leftJoin,
    'innerJoin' => $innerJoin,
    'select' => $select,
    'sortby' => 'bxSubscriber.id',
    'sortdir' => 'ASC',
    'return' => !empty($returnIds)
        ? 'ids'
        : 'data',
    'nestedChunkPrefix' => 'bxsender_',
);

// Merge all properties and run!
$pdoFetch->setConfig(array_merge($default, $scriptProperties), false);
$rows = $pdoFetch->run();

// Process rows
$output = array();
if (!empty($rows) && is_array($rows)) {
    foreach ($rows as $k => $row) {
        $row['idx'] = $pdoFetch->idx++;
        $row['link'] = $bxSender->getPageSubscribeManager($row);

        $output[] = $pdoFetch->getChunk($tpl, $row);
    }
}

$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
    $log .= '<pre class="msProductsLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}

// Return output
if (!empty($returnIds) && is_string($rows)) {
    $modx->setPlaceholder('bxSubscriber.log', $log);
    if (!empty($toPlaceholder)) {
        $modx->setPlaceholder($toPlaceholder, $rows);
    } else {
        return $rows;
    }
} elseif (!empty($toSeparatePlaceholders)) {
    $output['log'] = $log;
    $modx->setPlaceholders($output, $toSeparatePlaceholders);
} else {
    if (empty($outputSeparator)) {
        $outputSeparator = "\n";
    }
    $output['log'] = $log;
    $output = implode($outputSeparator, $output);

    if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
        $output = $pdoFetch->getChunk($tplWrapper, array(
            'output' => $output,
        ));
    }

    if (!empty($toPlaceholder)) {
        $modx->setPlaceholder($toPlaceholder, $output);
    } else {
        return $output;
    }
}