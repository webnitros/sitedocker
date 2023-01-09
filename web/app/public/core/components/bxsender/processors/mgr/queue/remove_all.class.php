<?php

/**
 * Remove an Queue
 */
class bxQueueRemoveAllProcessor extends modProcessor {
	public $objectType = 'bxQueue';
	public $classKey = 'bxQueue';


	/** {inheritDoc} */
	public function process() {
		$this->modx->removeCollection($this->classKey, array());

		return $this->success();
	}
}

return 'bxQueueRemoveAllProcessor';
