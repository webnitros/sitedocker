bxSender.panel.Settings = function (config) {
    config = config || {}
    Ext.apply(config, {
        cls: 'container',
        items: [
            {
                html: '<h2>' + _('bxsender') + '::' + _('bxsender_settings') + '</h2>',
                border: false,
                cls: 'modx-page-header container'
            }, {
                xtype: 'modx-tabs',
                border: true,
                id: 'bxsender-settings-tabs',
                stateful: true,
                stateId: 'bxsender-panel-settings',
                stateEvents: ['tabchange'],
                getState: function () {
                    var index = this.items.indexOf(this.getActiveTab())
                    return {activeTab: index}
                },
                hideMode: 'offsets',
                items: [
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
    bxSender.panel.Settings.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.panel.Settings, MODx.Panel)
Ext.reg('bxsender-panel-settings', bxSender.panel.Settings)
