<?php
/**
 * Get an Segment
 */
class bxSegmentGetProcessor extends modObjectGetProcessor {
	public $objectType = 'bxSegment';
	public $classKey = 'bxSegment';
	public $languageTopics = array('bxsender:default');
}

return 'bxSegmentGetProcessor';