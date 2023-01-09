<?php
if (!class_exists('bxQueueControllerProcessor')) {
    require_once dirname(__FILE__) . '/controller.class.php';
}
class bxQueueStateProcessor extends bxQueueControllerProcessor {
    public $classKey = 'bxQueue';
    public $action = 'state';
}
return 'bxQueueStateProcessor';