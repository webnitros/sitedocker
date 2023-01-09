<?php
if (!class_exists('bxQueueControllerProcessor')) {
    require_once dirname(__FILE__) . '/controller.class.php';
}
class bxQueueQueryProcessor extends bxQueueControllerProcessor {
	public $classKey = 'bxQueue';
    public $action = 'query';
}
return 'bxQueueQueryProcessor';