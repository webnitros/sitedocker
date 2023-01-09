bxSender.window.CreateMemberAdd = function (config) {
    config = config || {};
    this.ident = config.ident || 'subscriber' + Ext.id();
    Ext.applyIf(config, {
        title: _('bxsender_subscriber_btn_bulk_add_addresses'),
        width: 600,
        baseParams: {
            action: 'mgr/subscription/subscriber/members/import/add',
        }
        ,labelAlign: 'top'
        ,cls: 'container form-with-labels'
    });
    bxSender.window.CreateMemberAdd.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.window.CreateMemberAdd, bxSender.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'bxsender-combo-segment',
                fieldLabel: _('bxsender_subscriber_segment'),
                description: _('bxsender_subscriber_segment_desc'),
                name: 'segments[]',
                id: config.id + '-segments',
                anchor: '99%',
                allowBlank: false
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                name: 'of_users',
                id: config.id + '-of_users',
                boxLabel: _('bxsender_subscriber_of_users'),
            },
            {
                xtype: 'bxsender-combo-group',
                name: 'of_group',
                id: config.id + '-of_group',
                fieldLabel: _('bxsender_subscriber_of_group'),
            },
            {
                xtype: 'bxsender-combo-sendex-newsletter',
                name: 'of_sendex',
                id: config.id + '-of_sendex',
                fieldLabel: _('bxsender_subscriber_of_sendex'),
            },
            {
                fieldLabel: _('bxsender_subscriber_of_list'),
                xtype: 'textarea',
                id: config.id + '-list',
                name: 'list',
                anchor: '99%',
                height: 150
            },
            {
                style: 'margin: 10px 0px 0px 0; ',
                xtype: 'bxsender-description',
                id: config.id + '-list-description',
                html: _('bxsender_subscriber_list_desc'),
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_fullname'),
                name: 'replace_fullname',
                id: config.id + '-replace_fullname',
                anchor: '99%',
                allowBlank: true
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_user_id'),
                description: _('bxsender_subscriber_replace_user_id_desc'),
                name: 'replace_user_id',
                id: config.id + '-replace_user_id',
                anchor: '99%',
                allowBlank: true
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_search_user'),
                description: _('bxsender_subscriber_search_user_desc'),
                name: 'search_user',
                id: config.id + '-search_user',
                anchor: '99%',
                allowBlank: true
            }
        ];
    },

});
Ext.reg('bxsender-window-subscriber-member-add', bxSender.window.CreateMemberAdd);