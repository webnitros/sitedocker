bxSender.form.UpdateMailSender = function (config) {
    config = config || {}
    this.ident = config.ident || 'mailsender' + Ext.id()
    config.id = this.ident

    config.record = bxSender.config.mailsender
    config.enableSMTP = config.record.transport === 'smtp'
    config.testindSend = false
    config.SMTPFields = ['host', 'username', 'password', 'port', 'prefix']

    this.transportFields = ['from', 'from_name', 'reply_to', 'host', 'username', 'password', 'port', 'prefix']
    this.transportFieldsShow = {
        system: [],
        smtp: ['from', 'from_name', 'reply_to', 'host', 'username', 'password', 'port', 'prefix'],
        server: ['from', 'from_name', 'reply_to']
    }

    Ext.applyIf(config, {
        cls: 'container form-with-labels main-wrapper bxsender_buttons_settings'
        , labelAlign: 'left'
        , autoHeight: true
        , title: _('search_criteria')
        , labelWidth: 300
        , url: bxSender.config.connector_url
        , baseParams: {
            action: 'mgr/settings/mailsender/update'
        }
        , items: this.getFields(config)
        , style: {margin: '15px 30px'}
        , buttonAlign: 'top'
        , buttons: [
            {
                text: '<i class="icon icon-save"></i> ' + _('bxsender_btn_save'),
                cls: ' primary-button'
                , handler: function () {
                this.submit(this)
            }, scope: this
            }
        ]

        , defaults: {
            anchor: '80%'
        },

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
    bxSender.form.UpdateMailSender.superclass.constructor.call(this, config)
    this.on('afterrender', function () {
        var $this = this
        setTimeout(function () {
            $this.updateSendingFrequency()
            $this.SMTPconnection($this.config.record.transport)
        }, 300)
    })

    var $this = this
    this.on('success', function () {
        if ($this.testindSend) {
            $this.testindSend = false
            $this.checkConnectionCallBack()
        }

    })

    this.on('beforeSubmit', function (e) {
        if (e.config.record) {
            var values = e.form.getValues()
            bxSender.config.mailsender.method = values.method || 'ajax';
        }
    })

}
Ext.extend(bxSender.form.UpdateMailSender, MODx.FormPanel, {

    getFields: function (config) {

        return [

            {
                layout: 'form',
                items: [
                    {
                        xtype: 'bxsender-combo-transport',
                        cls: 'bxsender_form_settings_input',
                        fieldLabel: _('bxsender_mailsender_transport'),
                        name: 'transport',
                        id: config.id + '-transport',
                        anchor: '50%',
                        allowBlank: false,
                        value: config.record.transport || 'system',
                        listeners: {
                            'select': {
                                fn: function (f, value) {
                                    this.SMTPconnection(value.data.transport)
                                }, scope: this
                            }
                        }
                    },
                    {
                        xtype: 'displayfield',
                        style: {margin: '0px 0 15px 305px', color: '#666666'},
                        hideLabel: true,
                        name: 'transport_desc',
                        anchor: '70%',
                        id: config.id + '-transport_desc',
                        html: _('bxsender_mailsender_transport_' + config.record.transport + '_desc'),
                    },

                    {
                        xtype: 'bxsender-combo-mailsender-method',
                        cls: 'bxsender_form_settings_input',
                        fieldLabel: _('bxsender_mailsender_method'),
                        name: 'method',
                        id: config.id + '-method',
                        anchor: '50%',
                        allowBlank: false,
                        value: config.record.method || 'ajax',
                        listeners: {
                            'select': {
                                fn: function (f, value) {
                                    Ext.getCmp(this.config.id + '-method_desc').update(bxSender.config.mailsender_method[value.data.method])
                                }, scope: this
                            }
                        }
                    },
                    {
                        xtype: 'displayfield',
                        style: {margin: '0px 0 15px 305px', color: '#666666'},
                        hideLabel: true,
                        name: 'method_desc',
                        anchor: '100%',
                        id: config.id + '-method_desc',
                        html:bxSender.config.mailsender_method[config.record.method]
                    }
                ]
            },
            {
                xtype: 'fieldset'
                , title: _('bxsender_mailsender_fieldset_frequency')
                , id: config.id + '-frequency-fieldset'
                , cls: 'x-fieldset-checkbox-toggle'
                , collapsed: false
                , labelAlign: 'top'
                , collapsible: true
                , stateful: true
                , style: {margin: '15px 0 15px 305px', minWidth: '650px', maxWidth: '650px'}
                , stateEvents: ['collapse', 'expand']
                , items: [
                {
                    layout: 'column',

                    style: {padding: '5px 15px', minWidth: '650px', maxWidth: '650px'},
                    items: [
                        {
                            style: {minWidth: '60px', maxWidth: '60px'},
                            layout: 'form',
                            defaults: {msgTarget: 'under'},
                            border: false,
                            items: [
                                {
                                    xtype: 'numberfield',
                                    hideLabel: true,
                                    description: _('bxsender_mailsender_frequency_emails_desc'),
                                    name: 'frequency_emails',
                                    id: config.id + '-frequency_emails',
                                    anchor: '100%',
                                    allowBlank: false,
                                    enableKeyEvents: true,
                                    listeners: {
                                        'keyup': {
                                            fn: function (row, e, value) {
                                                this.updateSendingFrequency(row.getValue())
                                            }, scope: this
                                        }
                                    },
                                    value: config.record.frequency_emails || 10
                                }
                            ],
                        },
                        {
                            style: {minWidth: '50px', maxWidth: '50px'},
                            layout: 'form',
                            defaults: {msgTarget: 'under'},
                            cls: 'bxsender_displayfield',
                            border: false,
                            items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    html: _('bxsender_mailsender_frequency_message')
                                }
                            ]
                        }, {
                            style: {minWidth: '250px', maxWidth: '250px'}
                            , layout: 'form'
                            , defaults: {msgTarget: 'under'}
                            , border: false
                            , items: [
                                {
                                    xtype: 'bxsender-combo-frequency-interval',
                                    hideLabel: true,
                                    description: _('bxsender_mailsender_frequency_interval_desc'),
                                    name: 'frequency_interval',
                                    id: config.id + '-frequency_interval',
                                    anchor: '100%',
                                    allowBlank: false,
                                    listeners: {
                                        select: {
                                            fn: function (el, row) {
                                                this.updateSendingFrequency()
                                            }, scope: this
                                        },
                                    },
                                    value: config.record.frequency_interval || 5
                                }
                            ]
                        }, {
                            style: {minWidth: '160px', maxWidth: '160px'}
                            , layout: 'form'
                            , defaults: {msgTarget: 'under'}
                            , border: false
                            , cls: 'bxsender_displayfield'
                            , items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    name: 'frequency_day',
                                    anchor: '100%',
                                    id: config.id + '-frequency_day',
                                    html: _('bxsender_mailsender_frequency_day'),
                                    value: config.record.frequency_day || ''
                                }
                            ]
                        }
                    ]
                }
            ]
            },

            {
                layout: 'form',
                items: [
                    {
                        xtype: 'bxsender-description',
                        fieldLabel: _('bxsender_mailsender_spf'),
                        html: _('bxsender_mailsender_spf_desc'),
                        anchor: '100%',
                        style: {margin: '0px 0 15px 0px'}
                    },
                    {
                        xtype: 'bxsender-description',
                        fieldLabel: _('bxsender_mailsender_spf'),
                        html: _('bxsender_mailsender_spf_desc'),
                        anchor: '100%',
                        style: {margin: '0px 0 15px 0px'}
                    }
                ]
            },

            {
                layout: 'form',
                style: {marginBottom: '25px'},
                items: [

                    {
                        xtype: 'textfield',
                        cls: 'bxsender_form_settings_input',
                        fieldLabel: _('bxsender_mailsender_from'),
                        name: 'from',
                        id: config.id + '-from',
                        anchor: '50%',
                        allowBlank: false,
                        value: config.record.from || ''
                    },
                    {
                        xtype: 'textfield',
                        cls: 'bxsender_form_settings_input',
                        fieldLabel: _('bxsender_mailsender_from_name'),
                        name: 'from_name',
                        id: config.id + '-from_name',
                        anchor: '50%',
                        allowBlank: false,
                        value: config.record.from_name || ''
                    },
                    {
                        xtype: 'textfield',
                        cls: 'bxsender_form_settings_input',
                        fieldLabel: _('setting_bxsender_mailsender_reply_to'),
                        name: 'reply_to',
                        id: config.id + '-reply_to',
                        anchor: '50%',
                        value: config.record.reply_to || ''
                    }
                ]
            },
            {
                xtype: 'fieldset'
                , title: _('bxsender_mailsender_fieldset_smtp')
                , id: config.id + '-setting_smtp-fieldset'
                , cls: 'x-fieldset-checkbox-toggle'
                , hidden: !this.isShowField(config)
                , hideLabel: true
                , collapsed: false
                , labelAlign: 'left'
                , labelWidth: 150
                , collapsible: true
                , stateful: true
                , style: {margin: '15px 0 25px 305px', minWidth: '600px', maxWidth: '600px'}
                , stateEvents: ['collapse', 'expand'],
                items: [
                    {
                        layout: 'form',
                        cls: 'bxsender_setting_smtp',
                        style: {padding: '15px 15px', minWidth: '600px', maxWidth: '600px'},
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('setting_bxsender_mailsender_host'),
                                name: 'host',
                                id: config.id + '-host',
                                anchor: '100%',
                                value: config.record.host || '',
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: _('setting_bxsender_mailsender_port'),
                                name: 'port',
                                id: config.id + '-port',
                                anchor: '100%',
                                value: config.record.port || ''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: _('setting_bxsender_mailsender_username'),
                                name: 'username',
                                id: config.id + '-username',
                                anchor: '100%',
                                value: config.record.username || ''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: _('setting_bxsender_mailsender_password'),
                                name: 'password',
                                id: config.id + '-password',
                                anchor: '100%',
                                value: config.record.password || ''
                            },
                            {
                                xtype: 'bxsender-combo-prefix',
                                fieldLabel: _('setting_bxsender_mailsender_prefix'),
                                name: 'prefix',
                                id: config.id + '-prefix',
                                style: {maxWidth: '80px'},
                                value: config.record.prefix || ''
                            },
                        ]
                    }
                ]
            },
            {
                layout: 'form',
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: _('bxsender_mailsender_message_verification'),
                        name: 'message_verification',
                        id: config.id + '-message_verification',
                        anchor: '100%',
                        style: {minWidth: '250px', maxWidth: '250px'},
                        value: config.record.message_verification || '',
                    }, {
                        xtype: 'button',
                        cls: 'bxsender_btn_connect',
                        text: '<i class="icon icon-send"></i> ' + _('bxsender_btn_connect_send'),
                        handler: this.checkConnection,
                        scope: this
                    },
                    {
                        xtype: 'displayfield',
                        style: {margin: '0px 0 15px 305px', color: '#666666'},
                        hideLabel: true,
                        name: 'message_verification_desc',
                        anchor: '70%',
                        id: config.id + '-message_verification_desc',
                        html: _('bxsender_mailsender_message_verification_desc'),
                    }

                ]
            }
        ]
    },

    SMTPconnection: function (transport) {
        var showSmtp = false
        var transport_desc = _('bxsender_mailsender_transport_'+transport+'_desc')
        var fields = this.transportFieldsShow[transport]
        switch (transport) {
            case 'system':
                this.enableSMTP = false
                break
            case 'smtp':
                showSmtp = true
                this.enableSMTP = true
                break
            default:
                break
        }

        Ext.getCmp(this.config.id + '-transport_desc').update(transport_desc)
        var fieldset = Ext.getCmp(this.config.id + '-setting_smtp-fieldset')
        if (showSmtp) {
            fieldset.show()
        } else {
            fieldset.hide()
        }
        this.toggleFields(this.config.id, fields)
    },

    isShowField: function (config) {
        return this.enableSMTP
    },

    handleMailSenderFields: function (checkbox) {
        var type = checkbox.name.replace(/(^.*?_)/, '')

        var subject = Ext.getCmp(this.config.id + '-subject-' + type)
        var body = Ext.getCmp(this.config.id + '-body-' + type)
        if (checkbox.checked) {
            subject.enable().show()
            body.enable().show()
        }
        else {
            subject.hide().disable()
            body.hide().disable()
        }
    },
    updateSendingFrequency: function (emails) {

        if (emails === undefined) {
            emails = parseInt(Ext.getCmp(this.config.id + '-frequency_emails').value)
        }

        var interval = parseInt(Ext.getCmp(this.config.id + '-frequency_interval').value)
        var daily_emails = Ext.getCmp(this.config.id + '-frequency_day')
        var options = {
            only_daily: true,
            emails: emails,
            interval: interval
        }

        options.daily_emails = ~~(
            (1440 / options.interval) * options.emails
        )
        options.emails_per_second = (~~(
                ((options.daily_emails) / 86400) * 10)
        ) / 10
        options.daily_emails = options.daily_emails.toLocaleString()
        var tpl = new Ext.XTemplate(_('bxsender_mailsender_frequency_day'), {compiled: true})
        tpl.overwrite(daily_emails.el.dom, options)
    },

    // Проверка соединение
    checkConnection: function (grid, row, e) {
        Ext.Msg.confirm(_('bxsender_settings_mailsender_testing_message'), _('bxsender_settings_mailsender_testing_message_confirm'), function (e) {
            if (e == 'yes') {
                this.testindSend = true
                this.submit()
            } else {
                this.fireEvent('cancel', this.config)
            }
        }, this)
    },

    checkConnectionCallBack: function () {
        var email = Ext.getCmp(this.config.id + '-message_verification').getValue()
        var el = this.getEl()
        el.mask(_('loading'), 'x-mask-loading')

        MODx.Ajax.request({
            url: bxSender.config.connector_url
            , params: {
                action: 'mgr/settings/mailsender/connection',
                email: email,
            }
            , method: 'post'
            , scope: this
            , listeners: {
                'success': {
                    fn: function (response) {
                        MODx.msg.alert(_('success'), response.message)
                        el.unmask()
                    }, scope: this
                }
                , 'failure': {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message)
                        el.unmask()
                    }, scope: this
                }
            }
        })
    },

    toggleFields: function (form_id, fields) {

        var show = false;
        for (var i = 0; i < this.transportFields.length; i++) {
            var field = this.transportFields[i]

            show = false;
            if (fields.indexOf(field) !== -1) {
                show = true
            }
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
Ext.reg('bxsender-form-mailsender-update', bxSender.form.UpdateMailSender)