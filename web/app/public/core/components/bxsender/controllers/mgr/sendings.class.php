<?php
include_once dirname(dirname(__FILE__)) . '/manager.class.php';

class bxSenderMgrSendingsManagerController extends bxSenderManagerController
{

    public function addLanguageTopics()
    {
        return array('bxsender:sendings', 'bxsender:queue', 'bxsender:mailing', 'bxsender:setting', 'bxsender:manager');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {

        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/mailing/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/mailing/window.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/mailing/members.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/panel.js');

        // queues
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/queue/chart.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/queue/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/queue/form.js');


        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/undeliverable/grid.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/sendings/undeliverable/window.js');

        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/vendor/highstock/highcharts.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/vendor/highstock/modules/data.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/vendor/highstock/modules/drilldown.js');

        // Setting
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/settings/returnpath/form.js');
        $this->addJavascript($this->bxSender->config['jsUrl'] . 'mgr/widgets/settings/mailsender/form.js');


        $config = $this->bxSender->config;
        $config['returnpath'] = $this->bxSender->loadReturnPath()->toArray();
        $config['mailsender'] = $this->bxSender->loadMailSender()->toArray();

        $user = $this->modx->user->Profile->get(array('email', 'fullname'));
        $config['user_message'] = $this->modx->lexicon('bxsender_mailing_testing_user', $user);
        $config['user'] = $user;
        $config['testing'] = (boolean)false;
        $config['sending'] = (boolean)false;

        // Crontab task
        $crontab_task = '<div class="bxsender_pre_crontab"><pre class="bxsender_pre_crontab_task">*/1 * * * * /usr/bin/php '.$this->bxSender->config['corePath'].'cron/send.php > /dev/null 2>&1</pre></div>';
        $config['mailsender_method'] = array(
            'ajax' => $this->modx->lexicon('bxsender_mailsender_method_ajax_desc'),
            'crontab' => $this->modx->lexicon('bxsender_mailsender_method_crontab_desc', array('task' => $crontab_task))
        );




        $dir = '/core/elements/mailing/default.tpl';
        $config['default_message'] = "{*Шаблон письма располагается в папке '{$dir}'*}\n{include 'file:mailing/default.tpl'}";
        $this->addHtml('
		<script type="text/javascript">
            bxSender.config = ' . json_encode($config) . ';
            Ext.onReady(function() {
                MODx.add({xtype: "bxsender-panel-sendings"});
            });
		</script>');
    }
}