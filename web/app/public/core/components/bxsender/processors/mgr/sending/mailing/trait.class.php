<?php

/**
 * Create an Subscriber
 */
trait bxMailingTrait
{
    /**
     * Не даем создаватьвать рассылку с одинаковыми сервисами
     */
    public function hasMailingService()
    {
        $service = trim($this->getProperty('service'));
        if (!empty($service) and $service != strtolower($service)) {
            $id = $this->getProperty('id');
            if ($count = (boolean)$this->modx->getCount($this->classKey, array('id:!=' => $id, $service, 'service' => $service))) {
                $this->addFieldError('service', $this->modx->lexicon('bxsender_mailing_err_service', array('service' => $service)));
            }
        }
        $this->setProperty('service', $service);
    }
}