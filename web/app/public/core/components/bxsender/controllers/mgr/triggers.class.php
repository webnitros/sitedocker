<?php
include_once dirname(dirname(__FILE__)) . '/manager.class.php';

class bxSenderMgrTriggersManagerController extends bxSenderManagerController
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
        $this->addJavascript($this->bxSender->config['jsUrl'] . '/mgr/widgets/triggers/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/triggers/panel.js');
        $config = $this->bxSender->config;


        $this->addHtml('
		<script type="text/javascript">
            bxSender.config = ' . json_encode($config) . ';
            Ext.onReady(function() {
                MODx.add({xtype: "bxsender-panel-triggers"});
            });
		</script>');


    }

}