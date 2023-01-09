<?php
class bxStatOpens extends xPDOSimpleObject {
    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        }
        return parent::save();
    }
}