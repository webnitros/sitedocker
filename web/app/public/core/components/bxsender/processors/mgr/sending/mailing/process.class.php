<?php
require_once(dirname(__FILE__) . '/update.class.php');

class bxMailingProcessProcessor extends bxMailingUpdateProcessor
{

    public function initialize()
    {
        $response = parent::initialize();
        if ($response) {
            $countSubscribe = $this->object->getCountSubscribers();
            if ($countSubscribe == 0) {
                return $this->modx->lexicon('bxsender_mailing_err_status_process');
            }
        }
        return $response;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        // Удаляем все тестовые сообщения из очереди если они были!
        $criteria = array(
            'mailing_id' => $this->object->get('id'),
            'testing' => true,
        );
        if ($count = (boolean)$this->modx->getCount('bxQueue', $criteria)) {
            $c = $this->modx->newQuery('bxQueue');
            $c->command('DELETE');
            $c->where($criteria);
            $c->prepare();
            $c->stmt->execute();
        }

        // Добавление происходит только при первом старте
        if (!$this->object->get('start')) {

            // Получаем все ID подписок и добавляем их в очередь для дальнейшей обработки
            $mailing_id = $this->object->get('id');
            $segments = $this->object->getSegmentsIds();
            $count = $this->object->getCountQueue();
            if ($count == 0) {
                $VALUES = array();
                $q = $this->object->getCriteriasSubscribers($segments, array('id'));
                if ($q->prepare() && $q->stmt->execute()) {
                    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $VALUES[] = '(' . $mailing_id . ',' . $row['id'] . ')';
                    }
                }
                $VALUES = implode(',', $VALUES);
                $sql = "INSERT INTO {$this->modx->getTableName('bxQueue')} (mailing_id, subscriber_id) VALUES {$VALUES};";
                $response = $this->modx->exec($sql);
                if (!$response) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Error save queue".print_r($this->modx->errorInfo(),1), '', __METHOD__, __FILE__, __LINE__);
                    return false;
                }


            }
        }

        $this->object->setStatus('process');
        return true;
    }

    /**
     * Abstract the saving of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     * @return boolean
     */
    public function saveObject()
    {
        return true;
    }
}

return 'bxMailingProcessProcessor';
