<?php

/**
 * Get an Item
 */
class bxQueueGetProcessor extends modObjectGetProcessor
{
    /* @var bxQueue $object */
    public $object;
    public $objectType = 'message';
    public $classKey = 'bxQueue';
    public $permission = 'bxqueue_view_message';

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        return parent::process();
    }


    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $array = $this->object->toArray();
        $subject = $this->object->get('email_subject');
        switch ($this->object->get('state')) {
            case 'sent':
            case 'error':
                $output = $this->object->content();
                break;
            default:
                $output = $this->object->get('email_body');
                break;
        }
        unset($array['email_body']);
        unset($array['email_body_text']);
        $array['email_subject'] = $subject;
        $array['output'] = $output;
        return $this->success('', $array);
    }

}

return 'bxQueueGetProcessor';