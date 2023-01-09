<?php
class bxStatClicks extends xPDOSimpleObject {
    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        } else {
            $this->set('updatedon', time());
        }
        return parent::save();
    }

    /**
     * Сохраняем количество переходов
     */
    public function countClicksSave()
    {
        $count = $this->get('count');
        $count++;
        $this->set('count', $count);
        $this->save();
    }

}