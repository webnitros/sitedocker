bxSender.window.CreateSubscriber = function (config) {
    config = config || {}
    this.ident = config.ident || 'subscriber' + Ext.id()
    config.id = this.ident
    Ext.applyIf(config, {
        title: _('bxsender_window_create'),
        width: 700,
        baseParams: {
            action: 'mgr/subscription/subscriber/create',
        }
        , labelAlign: 'left'
        , labelWidth: 150
        , cls: 'container form-with-labels'
    })
    bxSender.window.CreateSubscriber.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.window.CreateSubscriber, bxSender.window.Default, {
    getFields: function (config) {

        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'}
            , {
                xtype: 'bxsender-combo-segment',
                fieldLabel: _('bxsender_subscriber_segment'),
                description: _('bxsender_subscriber_segment_desc'),
                name: 'segments',
                id: config.id + '-segments',
                anchor: '99%',
                allowBlank: !config.record !== undefined,
                hidden: config.record !== undefined
            }
            , {
                xtype: 'bxsender-combo-user',
                fieldLabel: _('bxsender_subscriber_user_id'),
                description: _('bxsender_subscriber_user_id_desc'),
                name: 'user_id',
                id: config.id + '-user_id',
                anchor: '99%',
                allowBlank: true
            }
            , {
                xtype: 'textfield',
                fieldLabel: _('bxsender_subscriber_email'),
                description: _('bxsender_subscriber_email_desc'),
                name: 'email',
                id: config.id + '-email',
                anchor: '99%',
                allowBlank: false
            }, {
                xtype: 'textfield',
                fieldLabel: _('bxsender_subscriber_fullname'),
                description: _('bxsender_subscriber_fullname_desc'),
                name: 'fullname',
                id: config.id + '-fullname',
                anchor: '99%',
                allowBlank: true
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('bxsender_subscriber_active'),
                name: 'active',
                id: config.id + '-active',
                anchor: '99%',
                allowBlank: false,
                boxLabel: _('bxsender_subscriber_active'),
                checked: config.record ? parseInt(config.record['active']) : true,
            }
        ]
    },

})
Ext.reg('bxsender-window-subscriber-create', bxSender.window.CreateSubscriber)

bxSender.window.UpdateSubscriber = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('bxsender_window_update'),
        baseParams: {
            action: 'mgr/subscription/subscriber/update',
        },
    })
    bxSender.window.UpdateSubscriber.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.window.UpdateSubscriber, bxSender.window.CreateSubscriber, {

    getFields: function (config) {
        return [
            {
                xtype: 'modx-tabs',
                border: true,
                stateful: true,
                stateId: 'bxsender-window-subscriber-update',
                stateEvents: ['tabchange'],
                id: 'bxsender-window-subscriber-update',
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())}
                },
                deferredRender: false,
                items: [
                    {
                        title: _('bxsender_subscriber_tab_main'),
                        layout: 'form',
                        hideMode: 'offsets',
                        border: false,
                        autoHeight: true,
                        items: bxSender.window.CreateSubscriber.prototype.getFields.call(this, config),
                    }, {
                        title: _('bxsender_subscriber_tab_members'),
                        items: [{
                            xtype: 'bxsender-grid-subscriber-members',
                            record: config.record,
                        }]
                    }
                ]
            }]
    }

})
Ext.reg('bxsender-window-subscriber-update', bxSender.window.UpdateSubscriber)