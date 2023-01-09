<?php

/**
 * Update an bxReturnPath
 */
class bxReturnPathUpdateProcessor extends modProcessor
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
        if (!$MailSender = $bxSender->loadReturnPath()) {
            return $this->failure('Не удалось загрузить настройки для соединения!');
        }

        $this->setCheckbox('enable');
        $this->setCheckbox('ssl');
        

        $MailSender->fromArray($this->getProperties());
        $MailSender->save();
        return $this->success();
    }
}
return 'bxReturnPathUpdateProcessor';
