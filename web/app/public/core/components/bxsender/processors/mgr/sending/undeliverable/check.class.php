<?php

class bxMailingCheckProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        /** @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxSender');
        $ReturnPath = $bxSender->loadReturnPath();

        if (!$ReturnPath->isEnable()) {
            return $this->failure('Обраный путь отключен');
        }

        // Скачиваем письма
        $response = $ReturnPath->getting();
        if ($response !== true) {
            return $this->failure($response);
        }

        $this->modx->error->reset();

        // Парсим
        $response = $ReturnPath->reading();
        if ($response !== true) {
            return $this->failure($response);
        }

        $this->modx->error->reset();

        return $this->success();
    }
}

return 'bxMailingCheckProcessor';