<?php
include_once dirname(dirname(dirname(__FILE__))) . '/trait.class.php';

class bxSubscriberMembersImportCSVProcessor extends modProcessor
{
    use bxSubscriberTrait;

    public $csv = null;
    public $file = array();


    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_propertyset');
    }

    public function getLanguageTopics()
    {
        return array('bxsender:subscription');
    }


    /** @var bxSender $bxSender */
    public $bxSender = null;

    public function initialize()
    {
        $this->file = $this->getProperty('file');


        /* verify file exists */
        if (empty($this->file)) return $this->modx->lexicon('properties_import_err_upload');
        if (empty($this->file) || !empty($this->file['error'])) return $this->modx->lexicon('properties_import_err_upload');

        $this->bxSender = $this->modx->getService('bxSender');


        $segments = $this->getProperty('segments');

        foreach ($segments as $segment) {
            if (!$count = (boolean)$this->modx->getCount('bxSegment', $segment)) {
                return $this->modx->lexicon('bxsender_subscriber_loader_failure_segment_id');
            }
        }


        $fields = $this->getProperty('fields');
        if (empty($fields)) {
            return $this->modx->lexicon('bxsender_subscriber_import_csv_fields_empty');
        } else {
            $fields = explode(',', $fields);
            $fields = array_map('trim', $fields);

            if (!in_array('email', $fields)) {
                return $this->modx->lexicon('bxsender_subscriber_import_csv_fields_empty_email');
            }
            /*if (!in_array('fullname', $fields)) {
                return $this->modx->lexicon('bxsender_subscriber_import_csv_fields_empty_fullname');
            }*/
        }
        return true;
    }


    public function process()
    {
        // Get data
        $replace_user_id = $this->setCheckbox('replace_user_id');
        $replace_fullname = $this->setCheckbox('replace_fullname');
        $search_user = $this->setCheckbox('search_user');
        $offset = $this->getProperty('offset', 0);
        $segments = $this->getProperty('segments');
        if (!is_array($segments)) {
            $segments = array($segments);
        }

        $fields = $this->getProperty('fields');
        $fields = explode(',', $fields);
        $fields = array_map('trim', $fields);

        // Parse
        $csv = $this->bxSender->loadClassCSV();
        $csv->fields = $fields;
        $csv->offset = $offset;
        $csv->delimiter = ';';
        $csv->encoding('WINDOWS-1251', 'UTF-8');
        $csv->file = $this->file['tmp_name'];
        if (!$csv->parse()) {
            return $this->failure($this->modx->lexicon('bxsender_subscriber_import_csv_error'));
        }

        // Add
        $this->addSubscribers($csv->data, $segments, $replace_fullname, $replace_user_id, $search_user);
        return $this->success('Подписчики импортированы');
    }

}

return 'bxSubscriberMembersImportCSVProcessor';