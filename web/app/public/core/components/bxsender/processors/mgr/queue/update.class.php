<?php

/**
 * Update an Segment
 */
class bxQueueUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'bxQueue';
    public $classKey = 'bxQueue';
    public $languageTopics = array('bxsender');
    public $permission = 'edit_document';
}

return 'bxQueueUpdateProcessor';
