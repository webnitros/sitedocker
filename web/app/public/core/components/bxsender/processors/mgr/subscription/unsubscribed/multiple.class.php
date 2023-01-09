<?php

class bxUnSubscribedMultipleProcessor extends modProcessor
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
        /** @var modProcessorResponse $response */
        foreach ($ids as $id) {
            $response = $bxSender->runProcessor($method, array('id' => $id));
           /* if ($response->isError()) {
                return $response->getResponse();
            }*/
            $this->modx->error->reset();
        }
        return $this->success();
    }

}

return 'bxUnSubscribedMultipleProcessor';