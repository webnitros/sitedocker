<?php
include_once dirname(dirname(__FILE__)) . '/manager.class.php';

class bxSenderMgrSubscriptionsManagerController extends bxSenderManagerController
{

    public function addLanguageTopics()
    {
        return array('bxsender:subscription');
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('subscribe');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {

        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/segment/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/segment/window.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/segment/members.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/subscriber/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/subscriber/window.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/unsubscribed/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/unsubscribed/window.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/subscriber/members.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/subscriber/import/add.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/subscriber/import/csv.js');

        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/subscription/panel.js');


        //sendex_connector_url
        $config = $this->bxSender->config;


        

        $config['sendex_connector_url'] = dirname(dirname($this->bxSender->config['connector_url'])).'/sendex/connector.php';


        
        $this->addHtml('
		<script type="text/javascript">
            bxSender.config = ' . json_encode($config) . ';
            Ext.onReady(function() {
                MODx.add({xtype: "bxsender-panel-subscriptions"});
            });
		</script>');

    }

}