<?php
if (!class_exists('bxQueueControllerProcessor')) {
    require_once dirname(__FILE__) . '/controller.class.php';
}
class bxQueueSendProcessor extends bxQueueControllerProcessor {
    public $classKey = 'bxQueue';
    public $action = 'send';
}
return 'bxQueueSendProcessor';