<?php

/**
 * Update an bxMailSender
 */
class bxMailSenderUpdateProcessor extends modProcessor
{
    public $languageTopics = array('bxsender');
    public $permission = 'edit_document';

    /**
     * @return bool
     */
    public function process()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        if (!$MailSender = $bxSender->loadMailSender()) {
            return $this->failure('Не удалось загрузить настройки для соединения!');
        }
        
        $MailSender->fromArray($this->getProperties());
        $MailSender->save();
        return $this->success();
    }


}

return 'bxMailSenderUpdateProcessor';
