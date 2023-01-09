<?php
class bxUnSubscribed extends xPDOSimpleObject {
    /** {inheritDoc} */
    public function save($cacheFlag = null) {
        if ($this->isNew()) {
            $this->set('createdon', time());
        }
        return parent::save($cacheFlag);
    }
}