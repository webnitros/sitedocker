<?php
if (!class_exists('bxQueueControllerProcessor')) {
    require_once dirname(__FILE__) . '/controller.class.php';
}
class bxQueueSendAllProcessor extends bxQueueControllerProcessor {
	public $objectType = 'message';
	public $classKey = 'bxQueue';

    /** {inheritDoc} */
    public function process() {

        $queues = $this->modx->getIterator($this->classKey);


        /** @var bxQueue $queue */
        foreach ($queues as $queue) {
            $queue->action('query');
        }

        return $this->success();
    }

}

return 'bxQueueSendAllProcessor';