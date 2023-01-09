<?php

class bxSubscriberRemoveUnConfirmedProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {

        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');

        /* @var bxSubscriber $object */
        $q = $this->modx->newQuery('bxSubscriber');
        $q->where(array(
            'sent_confirmation' => 1,
            'confirmed' => 0,
        ));
        if ($objectList = $this->modx->getCollection('bxSubscriber', $q)) {
            foreach ($objectList as $object) {
                $object->remove();
            }
        }
        return $this->success();
    }

}

return 'bxSubscriberRemoveUnConfirmedProcessor';