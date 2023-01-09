<?php
/**
 * Get a list of Queues
 */
class bxQueueGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxQueue';
    public $classKey = 'bxQueue';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('bxsender:default', 'bxsender:manager', 'bxsender:subscriber', 'bxsender:queue');


    /* @var xPDOQuery $statistic */
    public $statistic = null;


    /**
     * Получение статистики по записи
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function getStatisticSelect(xPDOQuery $c)
    {
        // Opens
        $c->leftJoin('bxStatOpens', 'Opens', 'Opens.queue_id = bxQueue.id');
        $c->select('Opens.id as opens,Opens.createdon as opens_createdon');

        // Unsubscribed
        $c->leftJoin('bxStatUnSubscribed', 'Unsubscribed', 'Unsubscribed.queue_id = bxQueue.id');
        $c->select('Unsubscribed.id as unsubscribed,Unsubscribed.createdon as unsubscribed_createdon');

        // UnDeliverable
        $c->leftJoin('bxStatUnDeliverable', 'Undeliverable', 'Undeliverable.queue_id = bxQueue.id');
        $c->select('Undeliverable.id as undeliverable,Undeliverable.createdon as undeliverable_createdon');

        // Clicks
        $c->leftJoin('bxStatClicks', 'Clicks', 'Clicks.queue_id = bxQueue.id');
        $c->select('Clicks.count as clicks,Clicks.createdon as clicks_createdon');

        return $c;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('bxMailing', 'bxMailing', 'bxMailing.id = bxQueue.mailing_id');
        $c->select($this->modx->getSelectColumns('bxQueue', 'bxQueue'));
        $c->select('bxMailing.subject as segment_name');


        $c = $this->getStatisticSelect($c);


        if ($query = $this->getProperty('query', null)) {
            $query = trim($query);
            $c->where(array(
                'bxQueue.email_to:LIKE' => "%{$query}%"
            ));
        }


        $date_start = trim($this->getProperty('date_start', null));
        if (!empty($date_start)) {
            $date_start = strtotime($date_start);
            $c->where(array(
                'bxQueue.createdon:>' => $date_start
                #'bxQueue.createdon:>' => date('Y-m-d H:i:s', $date_start)
            ));
        }

        $date_end = trim($this->getProperty('date_end', null));
        if (!empty($date_end)) {
            $date_end = strtotime('+1 days', strtotime($date_end));
            $c->where(array(
                'bxQueue.createdon:<' => $date_end
                #'bxQueue.createdon:<' => date('Y-m-d H:i:s', $date_end)
            ));
        }

        $mailing = trim($this->getProperty('mailing', null));
        if (!empty($mailing)) {
            $c->where(array(
                'bxQueue.mailing_id' => $mailing
            ));
        }

        $state = trim($this->getProperty('state', 'all'));
        if (!empty($state) and $state != 'all') {
            $c->where(array(
                'bxQueue.state' => $state
            ));
        }

        // Сохраняем запрос без статуса
        $this->statistic = $this->modx->newQuery($this->classKey, $c);


        // Критерий вводится отсюда чтобы не учитывать выборку данных по статусу
        $status = trim($this->getProperty('status', null));
        if (!empty($status)) {


            switch ($status) {
                case 'all':
                    break;
                case 'failure':
                    $c->where(array(
                        'failure' => 1,
                    ));
                    break;
                case 'unknown':
                    // unknown
                    $c->where(array(
                        'bxQueue.failure' => 0,
                        'Opens.queue_id:IS' => NULL,
                        'AND:Clicks.queue_id:IS' => NULL,
                        'AND:Undeliverable.queue_id:IS' => NULL,
                        'AND:Unsubscribed.queue_id:IS' => NULL
                    ));
                    break;
                default:

                    $alias = ucfirst($status);
                    $c->where(array(
                        $alias . '.id:>' => 0
                    ));

                    break;
            }
        }

        #$c->prepare(); echo '<pre>'; print_r($c->toSQL()); die;
        return $c;
    }


    /**
     * @return array
     */
    public function getStatistic()
    {
        $states = array(
            array(
                'field' => 'all',
                'caption' => $this->modx->lexicon('bxsender_chart_all_message'),
                'description' => $this->modx->lexicon('bxsender_chart_all_message_desc'),
                'count' => 0,
                'color' => '#FFFFFF',
            ),
            array(
                'field' => 'opens',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_opens'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_opens_desc'),
                'count' => 0,
                'color' => 'green',
            ),
            array(
                'field' => 'clicks',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_clicks'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_clicks_desc'),
                'count' => 0,
                'color' => '#EBC55D',
            ),
            array(
                'field' => 'unsubscribed',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_unsubscribed'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_unsubscribed_desc'),
                'count' => 0,
                'color' => 'blue',
            ),
            array(
                'field' => 'undeliverable',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_undeliverable'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_undeliverable_desc'),
                'count' => 0,
                'color' => 'red',
            ),
            array(
                'field' => 'failure',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_failure'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_failure_desc'),
                'count' => 0,
                'color' => '#7e0000',
            ),
            array(
                'field' => 'unknown',
                'caption' => $this->modx->lexicon('bxsender_queue_chart_unknown'),
                'description' => $this->modx->lexicon('bxsender_queue_chart_unknown_desc'),
                'count' => 0,
                'color' => '#ccc',
            ),
        );


        $rows = array();

        $stat = array(
            'all' => 0,
            'opens' => 0,
            'clicks' => 0,
            'unsubscribed' => 0,
            'undeliverable' => 0,
            'failure' => 0,
            'unknown' => 0,
        );


        $q = $this->modx->newQuery('bxQueue');
        $q->query['where'] = $this->statistic->query['where'];


        // Получаем общее количество
        $stat['all'] = $countTotal = $this->modx->getCount('bxQueue', $q);

        /*
         * failure - сообщения попавшие в ошибку
         * */
        $failure = $this->modx->newQuery('bxQueue');
        $failure->query['where'] = $this->statistic->query['where'];
        $failure->where(array('failure' => 1));
        $stat['failure'] = $failure = $this->modx->getCount('bxQueue', $failure);


        // Получае статистику
        $aliases = array('Opens', 'Clicks', 'UnDeliverable', 'UnSubscribed');
        foreach ($aliases as $alias) {
            $field = strtolower($alias);
            $q->leftJoin('bxStat' . $alias, $alias, $alias . '.queue_id = bxQueue.id');
            $q->select("COUNT({$alias}.id) as " . $field);
        }


        $unknown = $this->modx->newQuery('bxQueue');
        $unknown->query['where'] = $q->query['where'];
        $unknown->query['from'] = $q->query['from'];


        // unknown
        $unknown->where(array(
            'Opens.queue_id:IS' => NULL,
            'AND:Clicks.queue_id:IS' => NULL,
            'AND:UnDeliverable.queue_id:IS' => NULL,
            'AND:UnSubscribed.queue_id:IS' => NULL
        ));
        $unknown = $this->modx->getCount('bxQueue', $unknown);
        $stat['unknown'] = $unknown - $failure;


        if ($q->prepare() && $q->stmt->execute()) {
            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows[0] as $field => $count) {
                if (isset($stat[$field])) {
                    $stat[$field] = $count;
                }
            }
        }

        foreach ($stat as $field => $count) {
            foreach ($states as $key => $r) {
                if ($r['field'] == $field) {
                    $states[$key]['count'] = $count;
                    $countTotal = $countTotal + $count;
                }
            }
        }


        $response = array(
            'сhart' => array(
                'state' => $states,
                "statePercent" => $this->getItemsPercent($states, $countTotal),
            )
        );
        return $response;
    }


    /**
     * Вернет проценты
     * @param $states
     * @return array
     */
    public function getItemsPercent($states, $countTotal)
    {
        $response = [];
        foreach ($states as $i => $row) {
            $field = $row['field'];
            $caption = $row['caption'];
            $count = $row['count'];

            if ($countTotal != 0) {
                $value = $count > 0 ? $count / $countTotal * 100 : 0;
            } else {
                $value = 0;
            }

            if ($value != 0) {
                $value = (float)$value;
            }
            $response[$field] = array(
                'count' => $count,
                'percent' => $value
            );
        }
        return $response;
    }


    public function outputArray(array $array, $count = false)
    {
        if ($count === false) {
            $count = count($array);
        }

        $response = array(
            'success' => true,
            'total' => (string)$count,
            'results' => $array
        );

        if ($chart = $this->getStatistic()) {
            $response = array_merge($response, $chart);
        }

        $output = json_encode($response);
        if ($output === false) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Processor failed creating output array due to JSON error ' . json_last_error());
            return json_encode(array('success' => false));
        }
        return $output;
    }


    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        /* @var bxQueue $object */

        $array = $object->toArray();
        $array['actions'] = array();
        unset($array['email_body']);
        unset($array['email_request']);

        $service = strtolower($array['service']);


        $isMail = false;
        if ($service == 'bxsender' or $service == '') {
            $isMail = true;
        }


        $array['clicks'] = (int)$array['clicks'];
        $array['clicks_createdon'] = !empty($array['clicks_createdon']) ? $array['clicks_createdon'] : '';


        $array['opens'] = (boolean)$array['opens'];
        $array['unsubscribed'] = (boolean)$array['unsubscribed'];
        $array['undeliverable'] = (boolean)$array['undeliverable'];
        $array['failure'] = (boolean)$array['failure'];


        $state = $array['state'];
        $action = $array['action'];


        // Send
        if ($object->isSend()) {
            switch ($array['action']) {
                case 'query':

                    if ($isMail) {
                        $array['actions'][] = array(
                            'class' => '',
                            'button' => true,
                            'menu' => true,
                            'multiple' => true,
                            'action' => 'actionQuery',
                            'icon' => 'icon icon-play',
                            'title' => $this->modx->lexicon('bxsender_action_actionQuery'),
                        );
                    }
                    break;
                case 'mail':

                    // Query
                    if ($isMail and $array['state'] != 'error') {
                        $array['actions'][] = array(
                            'class' => '',
                            'button' => true,
                            'multiple' => true,
                            'menu' => true,
                            'icon' => 'icon icon-play',
                            'action' => 'actionQuery',
                            'title' => $this->modx->lexicon('bxsender_action_actionQuery'),
                        );
                    }

                    // Send
                    if ($state == 'query' or $state == 'waiting') {
                        $array['actions'][] = array(
                            'class' => '',
                            'button' => true,
                            'multiple' => true,
                            'menu' => true,
                            'icon' => 'icon icon-send',
                            'action' => 'actionSend',
                            'title' => $this->modx->lexicon('bxsender_action_actionSend'),
                        );

                    }

                    break;
                default:
                    break;
            }

        }


        $state = $array['state'];
        switch ($state) {
            case 'spam':
            case 'rejected':
            case 'soft-bounced':
            case 'error':
            case 'sent':
            case 'queued':
            case 'undeliverable':

                $array['actions'][] = array(
                    'class' => '',
                    'button' => true,
                    'menu' => false,
                    'action' => 'showMessage',
                    'icon' => 'icon icon-info',
                    'title' => $this->modx->lexicon('bxsender_queues_action_showMessage'),
                );

                break;
            default:
                break;
        }


        if ($action == 'mail') {

            switch ($state) {
                case 'sent':
                case 'spam':
                case 'soft-bounced':
                case 'rejected':
                case 'waiting':
                case 'error':

                    // show message mail
                    $array['actions'][] = array(
                        'class' => '',
                        'button' => true,
                        'menu' => false,
                        'action' => 'actionContent',
                        'icon' => 'icon icon-envelope-open',
                        'title' => $this->modx->lexicon('bxsender_queues_action_actionContent'),
                    );

                    break;

                default:
                    break;
            }
        }


        $settings = $this->modx->user->getSettings();
        if (!empty($settings['bxsender_dev'])) {
            switch ($state) {
                case 'prepare':
                case 'waiting':
                    break;
                default:

                    if ($isMail) {
                        $array['actions'][] = array(
                            'class' => '',
                            'button' => true,
                            'multiple' => false,
                            'menu' => true,
                            'icon' => 'icon icon-stethoscope',
                            'action' => 'testing',
                            'title' => $this->modx->lexicon('bxsender_queues_action_testingQueue'),
                        );
                    }
                    break;
            }

            // Remove
            $array['actions'][] = array(
                'class' => '',
                'button' => true,
                'multiple' => true,
                'menu' => true,
                'icon' => 'icon icon-trash-o',
                'action' => 'remove',
                'title' => $this->modx->lexicon('bxsender_action_removeQueue'),
            );
        }

        return $array;
    }

}

return 'bxQueueGetListProcessor';