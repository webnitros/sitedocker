<?php

/**
 * Update an Segment
 */
class bxSegmentUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'bxSegment';
    public $classKey = 'bxSegment';
    public $languageTopics = array('bxsender');
    public $permission = 'edit_document';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $unique = array();
        foreach ($unique as $tmp) {
            if ($this->modx->getCount($this->classKey, array('name' => $this->getProperty($tmp), 'id:!=' => $this->getProperty('id')))) {
                $this->addFieldError($tmp, $this->modx->lexicon('bxsender_segment_err_ae'));
            }
        }
        $this->setCheckbox('allow_subscription');
        $this->setCheckbox('active');
        return !$this->hasErrors();
    }

}

return 'bxSegmentUpdateProcessor';
