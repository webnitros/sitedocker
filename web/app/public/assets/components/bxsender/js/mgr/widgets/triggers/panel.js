bxSender.panel.Triggers = function (config) {
    config = config || {}
    Ext.apply(config, {
        cls: 'container',
        items: [{
            html: '<h2>' + _('bxsender') + '::' + _('bxsender_triggers') + '</h2>',
            border: false,
            cls: 'modx-page-header container'
        }, {
            xtype: 'modx-tabs',
            stateful: true,
            stateId: 'bxsender-panel-triggers',
            stateEvents: ['tabchange'],
            id: 'bxsender-triggers-tabs',
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())}
            },
            hideMode: 'offsets',
            items: [
                {
                    title: _('bxsender_triggers'),
                    deferredRender: true,
                    layout: 'anchor',
                    items: [
                        {
                            html: '<p>' + _('bxsender_triggers_intro') + '</p>'
                            , bodyCssClass: 'panel-desc'
                            , bodyStyle: 'margin-bottom: 10px'
                        }, {
                            xtype: 'bxsender-grid-triggers'
                        }
                    ]
                }
            ]
        }]
    })
    bxSender.panel.Triggers.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.panel.Triggers, MODx.Panel)
Ext.reg('bxsender-panel-triggers', bxSender.panel.Triggers)
