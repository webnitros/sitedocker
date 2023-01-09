<?php
/**
 * Get an Segment
 */
class bxUnSubscribedGetProcessor extends modObjectGetProcessor {
	public $objectType = 'bxUnSubscribed';
	public $classKey = 'bxUnSubscribed';
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

return 'bxUnSubscribedGetProcessor';