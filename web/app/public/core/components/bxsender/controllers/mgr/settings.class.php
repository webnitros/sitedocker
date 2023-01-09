<?php
include_once dirname(dirname(__FILE__)) . '/manager.class.php';

class bxSenderMgrSettingsManagerController extends bxSenderManagerController
{

    public function addLanguageTopics()
    {
        return array('bxsender:manager', 'bxsender:setting');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {

        // Setting
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/settings/returnpath/form.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/settings/mailsender/form.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/settings/panel.js');
        $config = $this->bxSender->config;

        $config['returnpath'] = $this->bxSender->loadReturnPath()->toArray();
        $config['mailsender'] = $this->bxSender->loadMailSender()->toArray();


        $this->addHtml('
		<script type="text/javascript">
            bxSender.config = ' . json_encode($config) . ';
            Ext.onReady(function() {
                MODx.add({xtype: "bxsender-panel-settings"});
            });
		</script>');


    }

}