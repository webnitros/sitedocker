bxSender.form.UpdateReturnPath = function (config) {
    config = config || {}
    this.ident = config.ident || 'returnpath' + Ext.id()
    config.id = this.ident

    config.record = bxSender.config.returnpath
    config.testindSend = false


    Ext.applyIf(config, {
        cls: 'container form-with-labels main-wrapper bxsender_buttons_settings'
        , labelAlign: 'left'
        , autoHeight: true
        , title: _('search_criteria')
        , labelWidth: 300
        , url: bxSender.config.connector_url
        , baseParams: {
            action: 'mgr/settings/returnpath/update'
        }
        , items: this.getFields(config)
        , style: {margin: '15px 30px'}
        , buttonAlign: 'top'
        , buttons: [
            {
                text: '<i class="icon icon-save"></i> ' + _('bxsender_btn_save'),
                cls: 'primary-button',
                handler: function () {
                    this.submit(this)
                }, scope: this
            }
        ],
        listeners: {},
        keys: [
            {
                key: MODx.config.keymap_save || 's',
                ctrl: true,
                shift: false,
                scope: this,
                fn: function () {
                    this.submit(false)
                }
            }, {
                key: MODx.config.keymap_save || 's',
                ctrl: true,
                shift: true,
                scope: this,
                fn: function () {
                    this.submit()
                }
            }
        ]
    })

    bxSender.form.UpdateReturnPath.superclass.constructor.call(this, config)


    var $this = this
    this.on('success', function () {
        if ($this.testindSend) {
            $this.testindSend = false
            $this.checkConnectionCallBack()
        }
    })


}
Ext.extend(bxSender.form.UpdateReturnPath, MODx.FormPanel, {
    getFields: function (config) {
        return [
            {
                layout: 'form',
                items: [

                    {
                        xtype: 'xcheckbox',
                        name: 'enable',
                        id: config.id + '-enable',
                        anchor: '60%',
                        allowBlank: false,
                        fieldLabel: _('bxsender_returnpath_enable'),
                        boxLabel: _('bxsender_returnpath_enable_check'),
                        checked: config.record ? parseInt(config.record['enable']) : true,
                        listeners: {
                            'check': {
                                fn: function (f, checked) {
                                    var fields = ['email', 'host', 'username', 'password', 'port', 'ssl','transport_desc','connect']
                                    this.toggleFields(config.id, checked, fields)

                                }, scope: this
                            }
                        }
                    },

                    {
                        xtype: 'bxsender-description',
                        id: config.id + '-enable_desc',
                        itemId: config.id + '-enable_desc',
                        style: {margin: '0px 0px 0 305px'},
                        hideLabel: true,
                        name: 'enable_desc',
                        anchor: '70%',
                        html: _('bxsender_returnpath_enable_desc')
                    },

                    {
                        xtype: 'textfield',
                        fieldLabel: _('setting_bxsender_returnpath_email'),
                        name: 'email',
                        id: config.id + '-email',
                        anchor: '60%',
                        value: config.record.email,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    }
                    , {
                        xtype: 'textfield',
                        fieldLabel: _('setting_bxsender_returnpath_host'),
                        name: 'host',
                        id: config.id + '-host',
                        anchor: '60%',
                        value: config.record.host,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: _('setting_bxsender_returnpath_username'),
                        name: 'username',
                        id: config.id + '-username',
                        anchor: '60%',
                        value: config.record.username,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: _('setting_bxsender_returnpath_password'),
                        name: 'password',
                        id: config.id + '-password',
                        anchor: '60%',
                        value: config.record.password,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: _('setting_bxsender_returnpath_port'),
                        name: 'port',
                        id: config.id + '-port',
                        anchor: '60%',
                        value: config.record.port,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    },
                    {
                        xtype: 'xcheckbox',
                        name: 'ssl',
                        id: config.id + '-ssl',
                        anchor: '60%',
                        boxLabel: _('setting_bxsender_returnpath_ssl'),
                        checked: config.record ? parseInt(config.record['ssl']) : true,

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    },
                    {
                        xtype: 'bxsender-description',
                        style: {margin: '15px 0px 0 305px'},
                        hideLabel: true,
                        name: 'transport_desc',
                        anchor: '70%',
                        id: config.id + '-transport_desc',
                        itemId: config.id + '-transport_desc',
                        html: _('bxsender_settings_returnpath_pop_desc'),

                        allowBlank: this.isEnable(config),
                        hidden: !this.isEnable(config)
                    }, {
                        xtype: 'button',
                        style: {margin: '15px 0px 0 305px'},
                        id: config.id + '-connect',
                        text: '<i class="icon icon-send"></i> ' + _('bxsender_btn_connect'),
                        handler: this.checkConnection,
                        scope: this,
                        hidden: !this.isEnable(config)
                    }

                    /*
                    * text: '<i class="icon icon-eye"></i> ' + _('bxsender_btn_connect'),
                handler: this.checkConnection,
                scope: this
                */
                ]
            }
        ]
    },

    isEnable: function (config) {
        var isEnable = false
        if (config.record) {
            isEnable = config.record.enable
        }
        return isEnable
    },

    // Проверка соединение
    checkConnection: function (grid, row, e) {
        Ext.Msg.confirm(_('bxsender_settings_returnpath_сonnection'),_('bxsender_settings_returnpath_сonnection_confirm'),function(e) {
            if (e == 'yes') {
                this.testindSend = true
                this.submit()
            } else {
                this.fireEvent('cancel',this.config);
            }
        },this);
    },


    checkConnectionCallBack: function () {

        var el = this.getEl()
        el.mask(_('loading'), 'x-mask-loading')
        MODx.Ajax.request({
            url: bxSender.config.connector_url
            ,params: {
                action: 'mgr/settings/returnpath/connection',
            }
            ,method: 'post'
            ,scope: this
            ,listeners: {
                'success':{fn:function(response) {
                    MODx.msg.alert(_('success'), response.message)
                    el.unmask()
                },scope:this}
                ,'failure':{fn:function(response) {
                    MODx.msg.alert(_('error'), response.message)
                    el.unmask()
                },scope:this}
            }
        });
    },


    // Проверка соединение
    resetError: function (grid, row, e) {
        this.processors.multiple('reseterror')
    },

    toggleFields: function (form_id, show, fields) {
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i]
            var source = Ext.getCmp(form_id + '-' + field)
            if (source === undefined) {
                console.error('Could not found field ' + field)
            } else {
                if (show) {
                    source.show()
                } else {
                    source.hide()
                }
                source.allowBlank = !show
            }
        }
    },

})
Ext.reg('bxsender-form-returnpath-update', bxSender.form.UpdateReturnPath)