<?php

class bxSubscriberMemberMultipleProcessor extends modProcessor
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

        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');
        foreach ($ids as $key) {
            $response = $bxSender->runProcessor('mgr/subscription/subscriber/members/' . $method, $key);
            if ($response->isError()) {
                return $response->getResponse();
            }
        }
        return $this->success();
    }
}

return 'bxSubscriberMemberMultipleProcessor';