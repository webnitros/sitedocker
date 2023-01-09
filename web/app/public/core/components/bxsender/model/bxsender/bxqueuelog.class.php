<?php

class bxQueueLog extends xPDOSimpleObject
{

    /** {inheritDoc} */
    public function updateQueue()
    {
        $dateopen = null;
        $state = null;
        $entry = $this->get('entry');
        if (isset($entry['state']) and !empty($entry['state'])) {
            $state = $entry['state'];
        }

        $result = true;
        $c = $this->xpdo->newQuery('bxQueue');
        $c->command('UPDATE');



        $operation = $this->get('operation');
        $processed = null;
        switch ($operation) {
            case 'open':
                // Фиксируем даты открыти на случай если произошла ошибка, чтобы возможно было открыть задание на отправку через какое то время
                $c->query['set']['processed_date_open'] = array(
                    'value' => time(),
                    'type' => true,
                );
                $processed = 1;
                break;
            case 'close':
                $processed = 0;
                break;
            case 'update':
                $processed = 1;
                break;
            default:
                break;
        }


        // Фиксируем открытие сообщения
        $c->query['set']['processed'] = array(
            'value' => $processed,
            'type' => true,
        );


        if ($state) {
            $c->query['set']['state'] = array(
                'value' => $state,
                'type' => true,
            );

            if ($state == 'error') {
                $c->query['set']['failure'] = array(
                    'value' => true,
                    'type' => true,
                );
            }
            switch ($state) {
                case 'error':
                case 'sent':
                case 'undeliverable':
                case 'unsubscribed':
                    $c->query['set']['datesent'] = array(
                        'value' => time(),
                        'type' => true,
                    );
                    $c->query['set']['completed'] = array(
                        'value' => true,
                        'type' => true,
                    );
                    break;
                default:
                    break;
            }
        }

        $c->where(array(
            'id' => $this->get('queue_id')
        ));
        $c->prepare();
        if (!$c->stmt->execute()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), 1), '', __METHOD__, __FILE__, __LINE__);
            $result = false;
        }
        return $result;
    }

    /** {inheritDoc} */
    public function save($cacheFlag = null)
    {
        $result = parent::save($cacheFlag);
        if ($result) {
            $result = $this->updateQueue();
        }
        return $result;
    }
}