<?php
/**
 * Get an Segment
 */
class bxUnDeliverableGetProcessor extends modObjectGetProcessor {
	public $objectType = 'bxUnDeliverable';
	public $classKey = 'bxUnDeliverable';
	public $languageTopics = array('bxsender:default');



    /**
     * Return the response
     * @return array
     */
    public function cleanup() {
        $array = $this->object->toArray();

        $array['query'] = $this->modx->toJSON($array['query']);
        
        return $this->success('', $array);
    }
    
}

return 'bxUnDeliverableGetProcessor';