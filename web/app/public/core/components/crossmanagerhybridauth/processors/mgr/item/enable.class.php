<?php
include_once dirname(__FILE__) . '/update.class.php';
class crossManagerHybridauthItemEnableProcessor extends crossManagerHybridauthItemUpdateProcessor
{
    public function beforeSet()
    {
        $this->setProperty('active', true);
        return true;
    }
}
return 'crossManagerHybridauthItemEnableProcessor';