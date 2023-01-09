bxSender.window.CreateunSubscribed = function (config) {
    config = config || {};
    this.ident = config.ident || 'unsubscribed' + Ext.id();
    Ext.applyIf(config, {
        title: _('bxsender_window_create'),
        width: 600,
        baseParams: {
            action: 'mgr/subscription/unsubscribed/create',
        },
    });
    bxSender.window.CreateunSubscribed.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.CreateunSubscribed, bxSender.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'}
            , {
                xtype: 'bxsender-combo-subscriber',
                fieldLabel: _('bxsender_unsubscribed_subscriber'),
                description: _('bxsender_unsubscribed_subscriber_desc'),
                name: 'subscriber_id',
                id: config.id + '-subscriber_id',
                anchor: '100%',
                allowBlank: false
            }
          /*  , {
                xtype: 'textfield',
                fieldLabel: _('bxsender_unsubscribed_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '100%',
                allowBlank: false
            }
            , {
                xtype: 'textarea',
                fieldLabel: _('bxsender_unsubscribed_description'),
                name: 'description',
                id: config.id + '-description',
                height: 75,
                anchor: '100%'
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('bxsender_unsubscribed_active'),
                name: 'active',
                id: config.id + '-active',
                anchor: '100%',
                allowBlank: false,
                boxLabel: _('bxsender_unsubscribed_active'),
                checked: config.record ? parseInt(config.record['active']) : true,
            }*/
        ];
    },

});
Ext.reg('bxsender-window-unsubscribed-create', bxSender.window.CreateunSubscribed);

bxSender.window.UpdateunSubscribed = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('bxsender_window_update'),
        baseParams: {
            action: 'mgr/subscription/unsubscribed/update',
        },
    });
    bxSender.window.UpdateunSubscribed.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.UpdateunSubscribed, bxSender.window.CreateunSubscribed);
Ext.reg('bxsender-window-unsubscribed-update', bxSender.window.UpdateunSubscribed);