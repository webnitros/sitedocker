<?php

/**
 * Send an Queue
 */
class bxQueueControllerProcessor extends modProcessor
{
    public $classKey = 'bxQueue';
    public $action = '';


    /** {inheritDoc} */
    public function initialize()
    {
        /* @var bxSubscriber $Subscribers */
        return true;
    }

    public function actions($queues)
    {
        /** @var bxQueue $queue */
        foreach ($queues as $queue) {

            switch ($this->action) {
                case 'send':
                    $response = true;
                    try {
                        if ($Mailing = $queue->loadMailing()) {
                            $Mailing->fulfillConditionsSending();
                            $queue->action($this->action);
                        }
                    } catch (ExceptionSending $e) {
                        $response = "Error: ".$e->getMessage();
                    } catch (Exception $e) {
                        $response = "Error: ".$e->getMessage();
                    }
                    if ($response !== true) {
                        exit($this->modx->toJSON($this->failure($response)));
                    }
                    break;
                default:
                    $queue->action($this->action);
                    break;
            }
        }
    }

    /** {inheritDoc} */
    public function process()
    {
        $id = $this->getProperty('id');
        if (!empty($id)) {
            $this->setProperty('ids', $id);
        }

        $ids = $this->getProperty('ids');
        if (!empty($ids)) {
            $ids = explode(',', $this->getProperty('ids'));
        }

        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('bxsender_queue_err_ns'));
        }

        $queues = $this->modx->getIterator($this->classKey, array('id:IN' => $ids));
        if (empty($this->action)) {
            return $this->failure($this->modx->lexicon('bxsender_queue_err_ns'));
        }
        $this->actions($queues);
        return $this->success();
    }

}

return 'bxQueueSendProcessor';