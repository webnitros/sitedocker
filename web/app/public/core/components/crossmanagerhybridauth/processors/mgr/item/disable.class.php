<?php
include_once dirname(__FILE__) . '/update.class.php';
class crossManagerHybridauthItemDisableProcessor extends crossManagerHybridauthItemUpdateProcessor
{
    public function beforeSet()
    {
        $this->setProperty('active', false);
        return true;
    }
}
return 'crossManagerHybridauthItemDisableProcessor';
