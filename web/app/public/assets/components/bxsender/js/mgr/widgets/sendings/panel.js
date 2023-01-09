bxSender.panel.Sendings = function (config) {
    config = config || {}

    config.loadStatistic = false;
    
    Ext.apply(config, {
        cls: 'container',
        id: 'bxsender-panel-sendings',
        items: [
            {
                html: '<h2>' + _('bxsender') + '::' + _('bxsender_sendings') + '</h2>',
                border: false,
                cls: 'modx-page-header container'
            }, {
                xtype: 'modx-tabs',
                border: true,
                id: 'bxsender-sendings-tabs',
                stateful: true,
                stateId: 'bxsender-panel-sendings',
                stateEvents: ['tabchange'],
                getState: function () {
                    var index = this.items.indexOf(this.getActiveTab())
                    if (index === 1 && !config.loadStatistic) {
                        config.loadStatistic = true;
                        Ext.getCmp('bxsender-form-queue').submit()
                    }
                    return {activeTab: index}
                },
                hideMode: 'offsets',
                items: [
                    {
                        title: _('bxsender_mailing'),
                        deferredRender: true,
                        layout: 'anchor',
                        items: [
                            {
                                html: '<p>' + _('bxsender_mailing_intro') + '</p>'
                                , border: false
                                , bodyCssClass: 'panel-desc'
                                , bodyStyle: 'margin-bottom: 10px'
                            }, {
                                xtype: 'bxsender-grid-mailing',
                            }
                        ]
                    },
                    {
                        title: _('bxsender_queue'),
                        deferredRender: true,
                        layout: 'anchor',
                        id: 'bxsender_queue_tab',
                        items: [
                            {
                                html: '<p>' + _('bxsender_queue_intro') + '</p>'
                                , border: false
                                , bodyCssClass: 'panel-desc'
                                , bodyStyle: 'margin-bottom: 10px'
                            }, {
                                xtype: 'bxsender-form-queue',
                                cls: 'main-wrapper'
                            }, {
                                xtype: 'bxsender-grid-queues',
                            }
                        ]
                    },
                    {
                        title: _('bxsender_undeliverables'),
                        deferredRender: true,
                        layout: 'anchor',
                        items: [
                            {
                                html: '<p>' + _('bxsender_undeliverables_intro') + '</p>'
                                , border: false
                                , bodyCssClass: 'panel-desc'
                                , bodyStyle: 'margin-bottom: 10px'
                            }, {
                                xtype: 'bxsender-grid-undeliverables',
                            }
                        ]
                    },
                    {
                        title: _('bxsender_mailsender'),
                        layout: 'anchor',
                        deferredRender: true,
                        items: [
                            {
                                html: '<p>' + _('bxsender_mailsender_intro') + '</p>'
                                , border: false
                                , bodyCssClass: 'panel-desc'
                                , bodyStyle: 'margin-bottom: 10px'
                            }, {
                                xtype: 'bxsender-form-mailsender-update'
                            }
                        ]
                    },
                    {
                        title: _('bxsender_returnpath'),
                        deferredRender: true,
                        layout: 'anchor',
                        items: [
                            {
                                html: '<p>' + _('bxsender_returnpath_intro') + '</p>'
                                , border: false
                                , bodyCssClass: 'panel-desc'
                                , bodyStyle: 'margin-bottom: 10px'
                            }, {
                                xtype: 'bxsender-form-returnpath-update'
                            }
                        ]
                    }
                ]
            }]
    })
    bxSender.panel.Sendings.superclass.constructor.call(this, config)

    this.timers()


}
Ext.extend(bxSender.panel.Sendings, MODx.Panel, {
    getStatistics: function () {

        var mailing = Ext.getCmp('bxsender-grid-mailing')

        MODx.Ajax.request({
            url: bxSender.config.connector_url
            , params: {
                action: 'mgr/sending/mailing/sending'
            }
            , listeners: {
                success: {
                    fn: function (response) {
                        //if (response.object.active_mailing) {
                            if (mailing) {
                                mailing.refresh()
                            }
                       //}
                    }, scope: this
                }
                , failure: {
                    fn: function (r) {

                    }, scope: this
                }
            }
        })

    },
    getMethod: function () {
        return bxSender.config.mailsender.method;
    },

    timers: function () {
        var time = 60
        var panel = this
        var m = Math.floor(1000 * time)
        setInterval(function () {
            if (panel.getMethod() === 'ajax') {
                panel.getStatistics()
            }
        }, m)
    }
})
Ext.reg('bxsender-panel-sendings', bxSender.panel.Sendings)