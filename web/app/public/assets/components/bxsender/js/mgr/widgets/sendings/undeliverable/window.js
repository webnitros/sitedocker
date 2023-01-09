bxSender.window.CreateunDeliverable = function (config) {
    config = config || {};
    this.ident = config.ident || 'undeliverable' + Ext.id();
    Ext.applyIf(config, {
        title: _('bxsender_window_create'),
        width: 600,
        baseParams: {
            action: 'mgr/subscription/undeliverable/create',
        },
    });
    bxSender.window.CreateunDeliverable.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.CreateunDeliverable, bxSender.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'}
            , {
                xtype: 'textfield',
                fieldLabel: _('bxsender_undeliverable_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '100%',
                allowBlank: false
            }
            , {
                xtype: 'textarea',
                fieldLabel: _('bxsender_undeliverable_description'),
                name: 'description',
                id: config.id + '-description',
                height: 75,
                anchor: '100%'
            },
            {
                xtype: 'xcheckbox',
                fieldLabel: _('bxsender_undeliverable_active'),
                name: 'active',
                id: config.id + '-active',
                anchor: '100%',
                allowBlank: false,
                boxLabel: _('bxsender_undeliverable_active'),
                checked: config.record ? parseInt(config.record['active']) : true,
            }
        ];
    },

});
Ext.reg('bxsender-window-undeliverable-create', bxSender.window.CreateunDeliverable);

bxSender.window.UpdateunDeliverable = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('bxsender_window_update'),
        baseParams: {
            action: 'mgr/subscription/undeliverable/update',
        },
    });
    bxSender.window.UpdateunDeliverable.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.UpdateunDeliverable, bxSender.window.CreateunDeliverable);
Ext.reg('bxsender-window-undeliverable-update', bxSender.window.UpdateunDeliverable);