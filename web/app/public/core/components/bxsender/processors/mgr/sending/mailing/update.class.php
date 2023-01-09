<?php

include_once dirname(__FILE__) . '/trait.class.php';

/**
 * Update an Mailing
 */
class bxMailingUpdateProcessor extends modObjectUpdateProcessor
{
    use bxMailingTrait;
    /* @var bxMailing $object */
    public $object;
    public $objectType = 'bxMailing';
    public $classKey = 'bxMailing';
    public $languageTopics = array('bxsender:mailing');
    public $permission = 'edit_document';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->setCheckbox('active');

        $start_by_timedon = $this->getProperty('start_by_timedon');
        if (!empty($start_by_timedon)) {
            $strtotime = strtotime($start_by_timedon);
            $this->setProperty('start_by_timedon', $strtotime);
        }


        $subject = trim($this->getProperty('subject'));
        if (empty($subject)) {
            $this->addFieldError('subject', $this->modx->lexicon('bxsender_mailing_err_subject'));
        }

        if ($this->setCheckbox('utm')) {

            $utm_source = trim($this->getProperty('utm_source'));
            if (empty($utm_source)) {
                $this->addFieldError('utm_source', $this->modx->lexicon('bxsender_mailing_err_utm_source'));
            }

            $utm_medium = trim($this->getProperty('utm_medium'));
            if (empty($utm_medium)) {
                $this->addFieldError('utm_medium', $this->modx->lexicon('bxsender_mailing_err_utm_medium'));
            }

            $utm_campaign = trim($this->getProperty('utm_campaign'));
            if (empty($utm_campaign)) {
                $this->addFieldError('utm_campaign', $this->modx->lexicon('bxsender_mailing_err_utm_campaign'));
            }
        }


        $service = trim($this->getProperty('service'));
        if ($service != 'bxsender' and $count = (boolean)$this->modx->getCount($this->classKey, array('service' => $service))) {
            $this->addFieldError('service', $this->modx->lexicon('bxsender_mailing_err_service', array('service' => $service)));
        }


        /* @var bxSender $bxSender */
        /* $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
         if (!$ReturnPath = $bxSender->loadMailSender()) {
             if ($bxSender->loadMailSender()->isSMTP()) {
                 if ($ReturnPath->get('email') != $MailSender->get('from')) {
                     $this->addFieldError('returnpath_id', $this->modx->lexicon('bxsender_subscriber_returnpath_id_err_from_smtp'));
                 }
             }
         }*/

        $this->hasMailingService();
        return !$this->hasErrors();
    }

}

return 'bxMailingUpdateProcessor';
