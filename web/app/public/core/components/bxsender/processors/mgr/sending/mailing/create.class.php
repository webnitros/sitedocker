<?php

include_once dirname(__FILE__) . '/trait.class.php';

/**
 * Create an Item
 */
class bxMailingCreateProcessor extends modObjectCreateProcessor
{
    use bxMailingTrait;
    /* @var bxMailing $object */
    public $object;
    public $objectType = 'bxMailing';
    public $classKey = 'bxMailing';
    public $languageTopics = array('bxsender:mailing');
    public $permission = 'new_document';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->hasMailingService();
        $this->setProperty('mode', 'new');
        $this->setCheckbox('active');
        return !$this->hasErrors();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function afterSave()
    {
        // copy in
        $tpl = 'mailing/default.tpl';
        $elements_path = $this->modx->getOption('pdotools_elements_path');
        $target = $elements_path . $tpl;

        // Копировать файлы шаблона во время создания новой рассылки. Если его там нету
        if (!file_exists($target)) {

            /** @var bxSender $bxSender */
            $bxSender = $this->modx->getService('bxSender');
            $cache = $this->modx->getCacheManager();

            // copy is
            $source = $bxSender->config['corePath'] . 'elements/' . $tpl;

            // process
            $cache->copyFile($source, $target);
        }

        return true;
    }

}

return 'bxMailingCreateProcessor';