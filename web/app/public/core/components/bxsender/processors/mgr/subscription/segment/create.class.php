<?php

/**
 * Create an Item
 */
class bxSegmentCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'bxSegment';
    public $classKey = 'bxSegment';
    public $languageTopics = array('bxsender');
    public $permission = 'new_document';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $unique = array();
        foreach ($unique as $tmp) {
            if ($this->modx->getCount($this->classKey, array('name' => $this->getProperty($tmp)))) {
                $this->addFieldError($tmp, $this->modx->lexicon('bxsender_segment_err_ae'));
            }
        }
        $this->setCheckbox('allow_subscription');
        $this->setCheckbox('active');
        return !$this->hasErrors();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray(array(
            'rank' => $this->modx->getCount($this->classKey),
        ));
        return parent::beforeSave();
    }

}

return 'bxSegmentCreateProcessor';