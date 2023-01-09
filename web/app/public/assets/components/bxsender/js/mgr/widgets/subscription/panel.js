bxSender.panel.Subscriptions = function (config) {
    config = config || {}
    Ext.apply(config, {
        cls: 'container',
        items: [{
            html: '<h2>' + _('bxsender') + '::' + _('bxsender_subscriptions') + '</h2>',
            border: false,
            cls: 'modx-page-header container'
        }, {
            xtype: 'modx-tabs',
            stateful: true,
            stateId: 'bxsender-panel-subscriptions',
            stateEvents: ['tabchange'],
            id: 'bxsender-subscriptions-tabs',
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())}
            },
            hideMode: 'offsets',
            items: [
                {
                    title: _('bxsender_subscribers'),
                    deferredRender: true,
                    layout: 'anchor',
                    items: [
                        {
                            html: '<p>' + _('bxsender_subscribers_intro') + '</p>'
                            , bodyCssClass: 'panel-desc'
                            , bodyStyle: 'margin-bottom: 10px'
                        }, {
                            xtype: 'bxsender-grid-subscriber'
                        }
                    ]
                },
                {
                    title: _('bxsender_segments'),
                    deferredRender: true,
                    layout: 'anchor',
                    items: [
                        {
                            html: '<p>' + _('bxsender_segments_intro') + '</p>'
                            , bodyCssClass: 'panel-desc'
                            , bodyStyle: 'margin-bottom: 10px'
                        }, {
                            xtype: 'bxsender-grid-segments'
                        }
                    ]
                },
                {
                    title: _('bxsender_unsubscribeds'),
                    deferredRender: true,
                    layout: 'anchor',
                    items: [
                        {
                            html: '<p>' + _('bxsender_unsubscribeds_intro') + '</p>'
                            , bodyCssClass: 'panel-desc'
                            , bodyStyle: 'margin-bottom: 10px'
                        }, {
                            xtype: 'bxsender-grid-unsubscribeds'
                        }
                    ]
                }
            ]
        }]
    })
    bxSender.panel.Subscriptions.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.panel.Subscriptions, MODx.Panel)
Ext.reg('bxsender-panel-subscriptions', bxSender.panel.Subscriptions)
