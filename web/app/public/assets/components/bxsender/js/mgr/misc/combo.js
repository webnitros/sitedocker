bxSender.combo.User = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'user_id'
        , fieldLabel: _('bxsender_subscriber')
        , hiddenName: config.name || 'user_id'
        , displayField: 'username'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['username', 'id', 'fullname']
        , pageSize: 20
        , url: MODx.modx23
            ? MODx.config.connector_url
            : MODx.config.connectors_url + 'security/user.php'
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_user')
        , baseParams: {
            action: MODx.modx23
                ? 'security/user/getlist'
                : 'getlist'
            , combo: 1
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <sup>({id})</sup> <strong>{username}</strong><br/>{fullname}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })
    bxSender.combo.User.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.User, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-user', bxSender.combo.User)

bxSender.combo.ReturnPath = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'user_id'
        , fieldLabel: _('bxsender_subscriber')
        , hiddenName: config.name || 'rp_id'
        , displayField: 'email'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['email', 'id']
        , pageSize: 20
        , url: bxSender.config.connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_returnpath')
        , baseParams: {
            action: 'mgr/settings/returnpath/getlist'
            , combo: 1
        }
    })
    bxSender.combo.ReturnPath.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.ReturnPath, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-returnpath', bxSender.combo.ReturnPath)

bxSender.combo.UserGroup = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'group_id'
        , fieldLabel: _('bxsender_subscribers')
        , hiddenName: config.name || 'group_id'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['name', 'id', 'description']
        , pageSize: 20
        , url: MODx.modx23
            ? MODx.config.connector_url
            : MODx.config.connectors_url + 'security/group.php'
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_group')
        , baseParams: {
            action: MODx.modx23
                ? 'security/group/getlist'
                : 'getlist'
            , combo: 0
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <tpl if="id"><sup>({id})</sup></tpl>\
                    <strong>{name}</strong><br/>{description}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })
    bxSender.combo.UserGroup.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.UserGroup, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-group', bxSender.combo.UserGroup)

bxSender.combo.SendexNewsletter = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'group_id'
        , fieldLabel: _('bxsender_subscribers')
        , hiddenName: config.name || 'group_id'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['name', 'id', 'description']
        , pageSize: 20
        , url: bxSender.config.sendex_connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_sendex_newsletter')
        , baseParams: {
            action: 'mgr/newsletter/getlist'
            , combo: 1
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <tpl if="id"><sup>({id})</sup></tpl>\
                    <strong>{name}</strong><br/>{description}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })
    bxSender.combo.SendexNewsletter.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.SendexNewsletter, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-sendex-newsletter', bxSender.combo.SendexNewsletter)

/*bxSender.combo.SegmentMulti = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype:'superboxselect'
        ,triggerAction: 'all'
        ,mode: 'remote'
        ,valueField: "id"
        ,displayField: "name"
        ,store: new Ext.data.JsonStore({
            id:'id',
            autoLoad: true,
            root:'results',
            fields: ['id', 'name', 'description'],
            url: bxSender.config.connector_url,
            baseParams:{
                action: 'mgr/subscription/segment/getlist'
                , combo: 1
            }
        })

    });
    bxSender.combo.SegmentMulti.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.combo.SegmentMulti, Ext.ux.form.SuperBoxSelect)
Ext.reg('bxsender-combo-segment-multi', bxSender.combo.SegmentMulti)*/

bxSender.combo.Segment = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'segment_id'
        , fieldLabel: _('bxsender_segment')
        , hiddenName: config.name || 'segment_id'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['id', 'name', 'description']
        , pageSize: 20
        , url: bxSender.config.connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_segment')
        , baseParams: {
            action: 'mgr/subscription/segment/getlist'
            , combo: 1
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <sup>({id})</sup> <strong>{name}</strong><br/>{description}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })
    bxSender.combo.Segment.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.Segment, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-segment', bxSender.combo.Segment)

bxSender.combo.Subscriber = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'subscriber_id'
        , fieldLabel: _('bxsender_segment')
        , hiddenName: config.name || 'subscriber_id'
        , displayField: 'email'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['id', 'email', 'segment_subject']
        , pageSize: 20
        , url: bxSender.config.connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_subscriber')
        , baseParams: {
            action: 'mgr/subscription/subscriber/getlist'
            , combo: 1
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <sup>({id})</sup> <strong>{email}</strong><br/>{segment_subject}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })
    bxSender.combo.Subscriber.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.Subscriber, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-subscriber', bxSender.combo.Subscriber)

bxSender.combo.Mailing = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'mailing_id'
        , fieldLabel: _('bxsender_mailing')
        , hiddenName: config.name || 'mailing_id'
        , displayField: 'subject'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['id', 'subject', 'createdon']
        , pageSize: 20
        , url: bxSender.config.connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_mailing')
        , baseParams: {
            action: 'mgr/sending/mailing/getlist'
            , combo: 1
        }
        , tpl: new Ext.XTemplate(
            '<tpl for=".">\
                <div class="x-combo-list-item">\
                    <sup>({id})</sup> <strong>{subject}</strong><br/>{createdon}\
                </div>\
            </tpl>'
            , {compiled: true}
        )
    })

    bxSender.combo.Mailing.superclass.constructor.call(this, config)

    this.on('loadexception', function (combo) {
        console.log(2121)
    }, this)


    this.on('collapse', function (combo) {
        combo.loaded = false
        combo.store.load()
    }, this)

}
Ext.extend(bxSender.combo.Mailing, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-mailing', bxSender.combo.Mailing)

bxSender.combo.Search = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    })
    bxSender.combo.Search.superclass.constructor.call(this, config)
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch()
        }, this)
    })
    this.addEvents('clear', 'search')
}
Ext.extend(bxSender.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this)
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        }
    },

    _triggerSearch: function () {
        this.fireEvent('search', this)
    },

    _triggerClear: function () {
        this.fireEvent('clear', this)
    },

})
Ext.reg('bxsender-combo-search', bxSender.combo.Search)
Ext.reg('bxsender-field-search', bxSender.combo.Search)

bxSender.combo.method = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        emptyText: 'Тип'
        , hiddenName: 'state'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['state', 'display']
            , data: [
                ['all', _('bxsender_queue_state_all')],
                ['sent', _('bxsender_queue_state_sent')],
                ['queue', _('bxsender_queue_state_queue')],
                //['no_message', _('bxsender_queue_state_no_message')],
                ['error', _('bxsender_queue_state_error')],
                ['waiting', _('bxsender_queue_state_waiting')],
                ['prepare', _('bxsender_queue_state_prepare')],
            ]
        })
        , mode: 'local'
        , valueField: 'state'
        , displayField: 'display'
    })
    bxSender.combo.method.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.method, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-state', bxSender.combo.method)


bxSender.combo.MailsenderMethod = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        hiddenName: 'method'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['method', 'display']
            , data: [
                ['ajax', _('bxsender_mailsender_method_ajax')],
                ['crontab', _('bxsender_mailsender_method_crontab')],
            ]
        })
        , mode: 'local'
        , valueField: 'method'
        , displayField: 'display'
    })
    bxSender.combo.MailsenderMethod.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.MailsenderMethod, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-mailsender-method', bxSender.combo.MailsenderMethod)


bxSender.combo.ShippingStatus = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        emptyText: 'Выберите стутас'
        , hiddenName: 'shipping_status'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['status', 'display']
            , data: [
                ['draft', _('bxsender_mailing_shipping_status_draft')],
                ['process', _('bxsender_mailing_shipping_status_process')],
                ['paused', _('bxsender_mailing_shipping_status_paused')],
                ['completed', _('bxsender_mailing_shipping_status_completed')],
            ]
        })
        , mode: 'local'
        , valueField: 'status'
        , displayField: 'display'
    })
    bxSender.combo.ShippingStatus.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.ShippingStatus, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-shipping-status', bxSender.combo.ShippingStatus)

bxSender.combo.FrequencyInterval = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        emptyText: _('bxsender_mailing_frequency_interval_select')
        , hiddenName: 'frequency_interval'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['interval', 'display']
            , data: [
                [1, _('bxsender_mailsender_frequency_interval_minute')],
                [2, _('bxsender_mailsender_frequency_interval_minute_2')],
                [5, _('bxsender_mailsender_frequency_interval_minute_5')],
                [10, _('bxsender_mailsender_frequency_interval_minute_10')],
                [15, _('bxsender_mailsender_frequency_interval_minute_15')],
                [30, _('bxsender_mailsender_frequency_interval_minute_30')]
            ]
        })
        , mode: 'local'
        , valueField: 'interval'
        , displayField: 'display'
    })
    bxSender.combo.FrequencyInterval.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.FrequencyInterval, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-frequency-interval', bxSender.combo.FrequencyInterval)

bxSender.combo.transport = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        emptyText: _('bxsender_mailsender_transport')
        , hiddenName: 'transport'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['transport', 'display']
            , data: [
                ['system', _('bxsender_mailsender_transport_system')],
                ['server', _('bxsender_mailsender_transport_server')],
                ['smtp', _('bxsender_mailsender_transport_smtp')],
            ]
        })
        , mode: 'local'
        , valueField: 'transport'
        , displayField: 'display'
    })
    bxSender.combo.transport.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.transport, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-transport', bxSender.combo.transport)

bxSender.combo.prefix = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        emptyText: _('bxsender_mailsender_prefix')
        , hiddenName: 'prefix'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['prefix', 'display']
            , data: [
                ['not', _('bxsender_mailsender_prefix_not')],
                ['ssl', _('bxsender_mailsender_prefix_ssl')],
                ['tls', _('bxsender_mailsender_prefix_tls')],
            ]
        })
        , mode: 'local'
        , valueField: 'prefix'
        , displayField: 'display'
    })
    bxSender.combo.prefix.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.prefix, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-prefix', bxSender.combo.prefix)

bxSender.combo.MailSender = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        name: 'mailsender_id'
        , fieldLabel: _('bxsender_mailsender')
        , hiddenName: config.name || 'mailsender_id'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['name', 'id']
        , pageSize: 20
        , url: bxSender.config.connector_url
        , editable: true
        , allowBlank: true
        , emptyText: _('bxsender_select_mailsender')
        , baseParams: {
            action: 'mgr/settings/mailsender/getlist'
            , combo: 1
        },
    })
    bxSender.combo.MailSender.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.combo.MailSender, MODx.combo.ComboBox)
Ext.reg('bxsender-combo-mailsender', bxSender.combo.MailSender)