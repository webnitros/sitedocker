<?php

class bxMailingSendingProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');

        $url = $this->modx->getOption('site_url') . ltrim($bxSender->config['assetsUrl'],'/').'cron.php';
        $ch = curl_init();
        $timeout = 2;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_exec($ch);
        curl_close($ch);

        return $this->success('', array(
            'active_mailing' => $this->getActiveMailing()
        ));
    }

    public function getActiveMailing()
    {
        $criteria = array(
            'active' => 1,
            'shipping_status' => 'process',
            'service' => 'bxsender',
            'start_mailing:<' => time(),
        );
        return (boolean)$this->modx->getCount('bxMailing', $criteria);
    }
}

return 'bxMailingSendingProcessor';