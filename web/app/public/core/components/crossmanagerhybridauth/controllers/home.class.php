<?php

/**
 * The home manager controller for crossManagerHybridauth.
 *
 */
class crossManagerHybridauthHomeManagerController extends modExtraManagerController
{
    /** @var crossManagerHybridauth $crossManagerHybridauth */
    public $crossManagerHybridauth;


    /**
     *
     */
    public function initialize()
    {
        $this->crossManagerHybridauth = $this->modx->getService('crossManagerHybridauth', 'crossManagerHybridauth', MODX_CORE_PATH . 'components/crossmanagerhybridauth/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['crossmanagerhybridauth:manager', 'crossmanagerhybridauth:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('crossmanagerhybridauth');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->crossManagerHybridauth->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/crossmanagerhybridauth.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/widgets/items/grid.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/widgets/items/windows.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->crossManagerHybridauth->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');

        $this->crossManagerHybridauth->config['date_format'] = $this->modx->getOption('crossmanagerhybridauth_date_format', null, '%d.%m.%y <span class="gray">%H:%M</span>');
        $this->crossManagerHybridauth->config['help_buttons'] = ($buttons = $this->getButtons()) ? $buttons : '';

        $this->addHtml('<script type="text/javascript">
        crossManagerHybridauth.config = ' . json_encode($this->crossManagerHybridauth->config) . ';
        crossManagerHybridauth.config.connector_url = "' . $this->crossManagerHybridauth->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "crossmanagerhybridauth-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .=  '<div id="crossmanagerhybridauth-panel-home-div"></div>';
        return '';
    }

    /**
     * @return string
     */
    public function getButtons()
    {
        $buttons = null;
        $name = 'crossManagerHybridauth';
        $path = "Extras/{$name}/_build/build.php";
        if (file_exists(MODX_BASE_PATH . $path)) {
            $site_url = $this->modx->getOption('site_url').$path;
            $buttons[] = [
                'url' => $site_url,
                'text' => $this->modx->lexicon('crossmanagerhybridauth_button_install'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1&encryption_disabled=1',
                'text' => $this->modx->lexicon('crossmanagerhybridauth_button_download'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1',
                'text' => $this->modx->lexicon('crossmanagerhybridauth_button_download_encryption'),
            ];
        }
        return $buttons;
    }
}