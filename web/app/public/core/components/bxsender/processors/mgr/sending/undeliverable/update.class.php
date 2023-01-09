<?php
/**
 * Update an Segment
 */
class bxUnDeliverableUpdateProcessor extends modObjectUpdateProcessor
{   
    /* @var bxUnDeliverable object*/
    public $object;
    public $objectType = 'bxUnDeliverable';
    public $classKey = 'bxUnDeliverable';
    public $languageTopics = array('bxsender');
    public $permission = 'edit_document';
}

return 'bxUnDeliverableUpdateProcessor';
