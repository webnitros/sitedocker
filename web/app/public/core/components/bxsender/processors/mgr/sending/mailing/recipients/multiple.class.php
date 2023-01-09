<?php

class bxMailingMemberMultipleProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }
        $mailing_id = null;

        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');
        foreach ($ids as $key) {
            $mailing_id = $key['mailing_id'];
            $response = $bxSender->runProcessor('mgr/sending/mailing/recipients/' . $method, $key);
            if ($response->isError()) {
                return $response->getResponse();
            }
        }


        /* @var bxMailing $object */
        if ($object = $this->modx->getObject('bxMailing', $mailing_id)) {
            $object->updateSubscribeCount();
            $object->save();
        }

        return $this->success();
    }
}

return 'bxMailingMemberMultipleProcessor';