<?php

class bxSenderManagerController extends modExtraManagerController
{

    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('subscribe');
    }


    public function addLanguageTopics()
    {
        return array();
    }

    public function getLanguageTopics()
    {
        $default = array('bxsender:default', 'bxsender:manager', 'bxsender:subscription');
        $array = $this->addLanguageTopics();
        $default = array_merge($default, $array);
        return $default;
    }

    /* @var bxSender $bxSender */
    public $bxSender;

    /**
     * @return void
     */
    public function initialize()
    {
        $this->addCss(MODX_ASSETS_URL . 'components/bxsender/css/mgr/bootstrap.buttons.min.css');
        $this->bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');
        $this->addCss($this->bxSender->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/bxsender.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/misc/processorx.js');

        $version = $this->modx->getVersionData();
        $modx23 = !empty($version) && version_compare($version['full_version'], '2.3.0', '>=');
        $this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.modx23 = ' . (int)$modx23 . ';
			bxSender.config.connector_url = "' . $this->bxSender->config['connectorUrl'] . '";
			bxSender.config.openbrowserUrl = "' . $this->bxSender->config['openbrowserUrl'] . '";
		});
		</script>');
        parent::initialize();
    }

}