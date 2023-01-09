<?php
/**
 * Get an Segment
 */
class bxSubscriberGetProcessor extends modObjectGetProcessor {
	public $objectType = 'bxSubscriber';
	public $classKey = 'bxSubscriber';
	public $languageTopics = array('bxsender:default');
}
return 'bxSubscriberGetProcessor';