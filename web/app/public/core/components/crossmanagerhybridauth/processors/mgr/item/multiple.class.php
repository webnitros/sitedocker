<?php

class crossManagerHybridauthMultipleProcessor extends modProcessor
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

        /** @var crossManagerHybridauth $crossManagerHybridauth */
        $crossManagerHybridauth = $this->modx->getService('crossManagerHybridauth');
        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $crossManagerHybridauth->runProcessor('mgr/item/' . $method, array('id' => $id), array(
                'processors_path' => MODX_CORE_PATH . 'components/crossmanagerhybridauth/processors/mgr/'
            ));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }


}

return 'crossManagerHybridauthMultipleProcessor';