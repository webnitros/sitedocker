<?php

class bxStatUnDeliverable extends xPDOSimpleObject
{
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


   /* public function afterExceedingCreateDeliveryError($cacheFlag = null)
    {
        // Создаем запись об ошибочной отписки
        $bxUnDeliverable = $this->modx->newObject('bxUnDeliverable');
        $bxUnDeliverable->fromArray($recipient);
        if (!$bxUnDeliverable->save()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error save bxUnDeliverable" . print_r($recipient, 1), '', __METHOD__, __FILE__, __LINE__);
        }

    }*/
}