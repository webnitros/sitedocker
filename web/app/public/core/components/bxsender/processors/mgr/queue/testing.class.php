<?php
require_once(dirname(__FILE__) . '/update.class.php');
class bxQueueTestingProcessor extends bxQueueUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'state' => 'prepare',
        );
        return true;
    }
}

return 'bxQueueTestingProcessor';