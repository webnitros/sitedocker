bxSender.window.CreateSegment = function (config) {
    config = config || {};
    this.ident = config.ident || 'segment' + Ext.id();
    Ext.applyIf(config, {
        title: _('bxsender_window_create'),
        width: 700,
        baseParams: {
            action: 'mgr/subscription/segment/create',
        }
        ,cls: 'container form-with-labels'
    });
    bxSender.window.CreateSegment.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.CreateSegment, bxSender.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'}
            , {
                xtype: 'textfield',
                fieldLabel: _('bxsender_segment_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '100%',
                allowBlank: false
            }
            , {
                xtype: 'textarea',
                fieldLabel: _('bxsender_segment_description'),
                name: 'description',
                id: config.id + '-description',
                height: 75,
                anchor: '100%'
            },
            {
                xtype: 'xcheckbox',
                name: 'active',
                id: config.id + '-active',
                anchor: '100%',
                hideLabel: true,
                allowBlank: false,
                boxLabel: _('bxsender_segment_active'),
                checked: config.record ? parseInt(config.record['active']) : true,
            },
            {
                xtype: 'xcheckbox',
                name: 'allow_subscription',
                id: config.id + '-allow_subscription',
                anchor: '100%',
                hideLabel: true,
                allowBlank: false,
                boxLabel: _('bxsender_segment_allow_subscription'),
                checked: config.record ? parseInt(config.record['show']) : true,
            }
        ];
    },

});
Ext.reg('bxsender-window-segment-create', bxSender.window.CreateSegment);

bxSender.window.UpdateSegment = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('bxsender_window_update'),
        baseParams: {
            action: 'mgr/subscription/segment/update',
        },
    });
    bxSender.window.UpdateSegment.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.UpdateSegment, bxSender.window.CreateSegment,{

    getFields: function (config) {
        return [
            {
                xtype: 'modx-tabs',
                border: true,
                stateful: true,
                stateId: 'bxsender-window-segment-update',
                stateEvents: ['tabchange'],
                id: 'bxsender-window-segment-update',
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())}
                },
                deferredRender: false,
                items: [
                    {
                        title: _('bxsender_segment_tab_main'),
                        layout: 'form',
                        hideMode: 'offsets',
                        border: false,
                        autoHeight: true,
                        items: bxSender.window.CreateSegment.prototype.getFields.call(this, config),
                    },{
                        title: _('bxsender_segment_tab_members'),
                        items: [{
                            xtype: 'bxsender-grid-segment-members',
                            cls: '',
                            record: config.record,
                        }]
                    }
                ]
            }]
    }

});
Ext.reg('bxsender-window-segment-update', bxSender.window.UpdateSegment);