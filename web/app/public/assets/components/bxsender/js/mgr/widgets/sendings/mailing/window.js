bxSender.window.CreateMailing = function (config) {
    config = config || {}
    this.ident = config.ident || 'mailing' + Ext.id()
    config.id = this.ident

    Ext.applyIf(config, {
        title: _('bxsender_window_create'),
        width: 700,
        baseParams: {
            action: 'mgr/sending/mailing/create',
        },
        bodyCssClass: 'tabs',
    })
    bxSender.window.CreateMailing.superclass.constructor.call(this, config)

    this.on('success', function (data) {
        if (data.a.result.object) {
            // Авто запуск при создании новой подписик
            if (data.a.result.object.mode) {
                if (data.a.result.object.mode === 'new') {
                    var grid = Ext.getCmp('bxsender-grid-mailing')
                    grid.update(grid, '', {data: data.a.result.object})
                }
            }
        }
    }, this)

}
Ext.extend(bxSender.window.CreateMailing, bxSender.window.Default, {
    filters: {},
    getFields: function (config) {
        return this.getFieldsMessage(config)
    },

    getButtons: function (config) {
        var buttons = [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close()
            }
        }]

        if (!this.isBlocked(config)) {
            buttons.push({
                text: config.saveBtnText || _('save'),
                cls: 'primary-button',
                scope: this,
                handler: function () {
                    this.submit(this.config.xtype !== 'bxsender-window-mailing-update')
                }
            })
        }

        return buttons
    },

    isBlocked: function (config) {

        if (!config.record) {
            return false
        }

        if (config.record.object['service'] !== 'bxsender') {
            return true
        }
        switch (config.record.object['shipping_status']) {
            case 'process':
            case 'completed':
                return true
                break
            default:
                break
        }
        return false
    },

    isService: function (config) {
        if (!config.record) {
            return false
        }
        return config.record.object['service'] !== 'bxsender'
    }

    , getFieldsMessage: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},

            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .7
                        , layout: 'form'
                        , defaults: {msgTarget: 'under'}
                        , border: false
                        , style: {margin: '0 10px 0 0'}
                        , items: [
                        {
                            name: 'subject',
                            xtype: 'textfield',
                            id: config.id + '-subject',
                            width: '95%',
                            fieldLabel: _('bxsender_mailing_subject'),
                            hideLabels: false,
                            readOnly: this.isBlocked(config)
                        }
                    ]
                    }, {
                        layout: 'form'
                        , defaults: {msgTarget: 'under'}
                        , border: false
                        , style: 'margin-top: 38px;'
                        , items: [
                            {
                                xtype: 'button',
                                description: _('bxsender_mailing_btn_open_tips'),
                                cls: 'bxsender_button_tips',
                                id: config.id + '-button-submit',
                                text: _('bxsender_mailing_btn_open_tips') + ' <i class="icon icon-question"></i>',
                                scope: this,
                                handler: function () {
                                    MODx.load({
                                        xtype: 'bxsender-window-names',
                                    }).show()
                                }
                            }
                        ]
                    }, {
                        layout: 'form'
                        , defaults: {msgTarget: 'under'}
                        , border: false
                        , style: 'margin-top: 17px;'
                        , items: [
                            {
                                xtype: 'displayfield',
                                hiddenLabel: true,
                                cls: 'bxsender_button_open_message',
                                id: config.id + '-open_message',
                                html: config.record ? '<a target="_blank" href="../assets/components/bxsender/action/openbrowser/index.php?mailing_id=' + config.record.object['id'] + '">' + _('bxsender_mailing_btn_open_message') + '</a> <i class="icon icon-external-link"></i>' : '',
                                scope: this
                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'textarea',
                id: config.id + '-message',
                fieldLabel: _('bxsender_mailing_message'),
                name: 'message',
                anchor: '99%',
                height: 300,
                readOnly: this.isBlocked(config)
            }
        ]
    }
    , getFieldsSetting: function (config) {

        return [

            {
                xtype: 'fieldset'
                , title: _('bxsender_mailing_utm_title')
                , id: config.id + '-utm-fieldset'
                , cls: 'x-fieldset-checkbox-toggle'
                , hideLabel: true
                , collapsed: false
                , labelAlign: 'top'
                , collapsible: false
                , stateful: true
                , stateEvents: ['collapse', 'expand']
                , style: {margin: '0px 0 15px 0'}
                , items: [
                {
                    xtype: 'xcheckbox',
                    name: 'utm',
                    hideLabel: true,
                    id: config.id + '-utm',
                    boxLabel: _('bxsender_mailing_utm'),
                    checked: config.record ? parseInt(config.record.object['utm']) : false,
                    listeners: {
                        'check': {
                            fn: function (f, checked) {
                                var utmSource = Ext.getCmp(config.id + '-utm_source')
                                var utmMedium = Ext.getCmp(config.id + '-utm_medium')
                                var utmCampaign = Ext.getCmp(config.id + '-utm_campaign')
                                if (checked) {
                                    utmSource.show()
                                    utmMedium.show()
                                    utmCampaign.show()
                                } else {
                                    utmSource.hide()
                                    utmMedium.hide()
                                    utmCampaign.hide()
                                }
                                utmSource.allowBlank = !checked
                                utmMedium.allowBlank = !checked
                                utmCampaign.allowBlank = !checked
                            }, scope: this
                        }
                    }
                },
                {
                    xtype: 'textfield',
                    fieldLabel: _('bxsender_mailing_utm_source'),
                    description: _('bxsender_mailing_utm_source_desc'),
                    name: 'utm_source',
                    id: config.id + '-utm_source',
                    anchor: '99%',
                    allowBlank: false,
                    hidden: config.record ? !parseInt(config.record.object['utm']) : true,
                },
                {
                    xtype: 'textfield',
                    fieldLabel: _('bxsender_mailing_utm_medium'),
                    description: _('bxsender_mailing_utm_medium_desc'),
                    name: 'utm_medium',
                    id: config.id + '-utm_medium',
                    anchor: '99%',
                    allowBlank: false,
                    hidden: config.record ? !parseInt(config.record.object['utm']) : true,
                },
                {
                    xtype: 'textfield',
                    fieldLabel: _('bxsender_mailing_utm_campaign'),
                    description: _('bxsender_mailing_utm_campaign_desc'),
                    name: 'utm_campaign',
                    id: config.id + '-utm_campaign',
                    anchor: '99%',
                    allowBlank: false,
                    hidden: config.record ? !parseInt(config.record.object['utm']) : true,
                }
            ]
            },
            {
                layout: 'column',
                items: [
                    /*{
                        columnWidth: .5
                        , layout: 'form'
                        , defaults: {msgTarget: 'under'}
                        , border: false
                        , style: {margin: '0 10px 0 0'}
                        , items: [

                    ]
                    }, */{
                        columnWidth: .5
                        , layout: 'form'
                        , defaults: {msgTarget: 'under'}
                        , border: false
                        , style: {margin: 0}
                        , items: [
                            {
                                name: 'service',
                                xtype: 'textfield',
                                id: config.id + '-service',
                                width: '95%',
                                fieldLabel: _('bxsender_mailing_service'),
                                description: _('bxsender_mailing_service_desc'),
                                //hidden: true
                            },
                            {
                                xtype: 'xcheckbox',
                                name: 'start_by_time',
                                hideLabel: true,
                                id: config.id + '-start_by_time',
                                boxLabel: _('bxsender_mailing_start_by_time'),
                                checked: config.record ? parseInt(config.record.object['start_by_time']) : false,
                                listeners: {
                                    'check': {
                                        fn: function (f, checked) {
                                            var startMailing = Ext.getCmp(config.id + '-start_by_timedon')
                                            startMailing.allowBlank = !checked
                                            if (startMailing.el !== undefined) {
                                                var parent = Ext.get(startMailing.el.dom.parentNode.parentNode)
                                                if (checked) {
                                                    parent.removeClass('x-hide-display')
                                                } else {
                                                    parent.addClass('x-hide-display')
                                                }
                                            }

                                            if (checked) {
                                                // startMailing.setWidth(250)
                                                startMailing.show()
                                            } else {
                                                //startMailing.hide()
                                            }

                                            startMailing.hidden = !checked
                                            startMailing.allowBlank = !checked
                                        }, scope: this
                                    }
                                }
                            },

                            {
                                xtype: 'xdatetime',
                                fieldLabel: _('bxsender_mailing_start_by_timedon'),
                                description: _('bxsender_mailing_start_by_timedon_desc'),
                                name: 'start_by_timedon',
                                id: config.id + '-start_by_timedon',
                                dateFormat: 'd.m.Y',
                                timeFormat: 'H:i',
                                allowBlank: true,
                                width: 250,
                            },
                            {
                                xtype: 'xcheckbox',
                                name: 'active',
                                hideLabel: true,
                                id: config.id + '-active',
                                boxLabel: _('bxsender_mailing_active'),
                                checked: config.record ? parseInt(config.record.object['active']) : true,
                            }
                        ]
                    }
                ]
            }
            ,
            {
                xtype: 'textarea',
                fieldLabel: _('bxsender_mailing_description'),
                name: 'description',
                id: config.id + '-description',
                height: 75,
                anchor: '99%'
            }
        ]
    }

    , getFieldsTesting: function (config) {
        return [
            {
                xtype: 'xcheckbox',
                name: 'send_user',
                id: config.id + '-send_user',
                boxLabel: bxSender.config.user_message,
                checked: true,
                hideLabel: true
            },
            {
                xtype: 'xcheckbox',
                name: 'delete_after_sending',
                hideLabel: true,
                id: config.id + '-delete_after_sending',
                boxLabel: _('bxsender_mailing_delete_after_sending'),
                checked: config.record ? parseInt(config.record['delete_after_sending']) : true,
            }
            , {
                xtype: 'textarea',
                id: config.id + '-send_emails',
                name: 'send_emails',
                hideLabel: true,
                anchor: '99%',
                style: {margin: '0px 0 15px 0', width: 75},
                width: '95%',
                height: 150,
            },

            {
                xtype: 'button',
                id: config.id + '-button-testings',
                text: _('bxsender_mailing_testing_btn'),
                cls: 'primary-button',
                handler: function () {
                    Ext.getCmp(config.id).testing(config)
                    //Ext.getCmp(config.id).submit(false)
                }
            }
        ]
    }

})
Ext.reg('bxsender-window-mailing-create', bxSender.window.CreateMailing)

bxSender.window.UpdateMailing = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('bxsender_window_update'),
        width: 900,
        height: 150,
        minHeight: 150,
        baseParams: {
            action: 'mgr/sending/mailing/update',
        }
    })
    bxSender.window.UpdateMailing.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.window.UpdateMailing, bxSender.window.CreateMailing, {

    openBrowse: function (config) {
        var form = this.fp.getForm()
        var record = form.getValues()
        bxSender.utils.openMailingBrowse(record.id)
    },
    testing: function (config) {

        var form = this.fp.getForm()
        var record = form.getValues()

        var el = this.getEl()
        el.mask(_('loading'), 'x-mask-loading')
        MODx.Ajax.request({
            url: bxSender.config.connector_url
            , params: {
                action: 'mgr/sending/mailing/testing'
                , mailing_id: record.id
                , send_user: record.send_user
                , send_emails: record.send_emails
                , delete_after_sending: record.delete_after_sending
            }
            , listeners: {
                success: {
                    fn: function (response) {
                        MODx.msg.alert(_('bxsender_mailing_testing_title'), response.message)
                        el.unmask()
                    }, scope: this
                }, failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('bxsender_error'), response.message)
                        el.unmask()
                    }, scope: this
                }
            }
        })

    },

    getFields: function (config) {

        var tab = []

        tab.push({
            title: _('bxsender_mailing_tab_message'),
            layout: 'form',
            hideMode: 'offsets',
            border: false,
            autoHeight: true,
            items: this.getFieldsMessage(config),
        })

        if (this.isBlocked(config) && !this.isService(config)) {

            // Отчет по рассылке
            tab.push({
                title: _('bxsender_mailing_tab_report'),
                id: 'bxsender-window-mailing-update-tab-report',
                layout: 'form',
                hideMode: 'offsets',
                border: false,
                autoHeight: true,
                items: this.getFieldsReport(config)
            })
        } else {

            // Блок настроек
            tab.push({
                title: _('bxsender_mailing_tab_setting'),
                layout: 'form',
                hideMode: 'offsets',
                border: false,
                autoHeight: true,
                items: this.getFieldsSetting(config)
            })

            // Блок списка сегментов
            if (!this.isService(config)) {
                tab.push({
                    title: _('bxsender_mailing_tab_recipients'),
                    items: [{
                        xtype: 'bxsender-grid-mailing-recipients',
                        record: config.record,
                    }]
                })

                // Блок тестирования отправки сообщений
                tab.push({
                    title: _('bxsender_mailing_tab_testing'),
                    layout: 'form',
                    hideMode: 'offsets',
                    border: false,
                    autoHeight: true,
                    items: this.getFieldsTesting(config),
                })
            }
        }
        return [
            {
                xtype: 'modx-tabs',
                border: true,
                stateful: true,
                stateId: 'bxsender-window-mailing-update',
                stateEvents: ['tabchange'],
                id: 'bxsender-window-mailing-update',
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())}
                },
                cls: 'main-wrapper',
                deferredRender: false,
                items: tab
            }]
    }
    , getFieldsReport: function (config) {

        var fields = []

        var allowedFields = ['createdon', 'updatedon', 'service', 'shipping_status', 'start_by_time', 'start_by_timedon', 'utm', 'utm_campaign', 'utm_medium', 'utm_source', 'queue_count', 'id']

        var utm = config.record.object['utm']
        var start_by_time = config.record.object['start_by_time']

        var value, field = ''
        for (var i in allowedFields) {
            field = allowedFields[i]

            if (config.record.object[field] !== undefined) {
                value = config.record.object[field]

                if (!utm) {
                    if (field === 'utm_campaign' || field === 'utm_medium' || field === 'utm_source') {
                        continue
                    }
                }
                if (!start_by_time) {
                    if (field === 'start_by_timedon') {
                        continue
                    }
                }

                if (field === 'queue_count') {
                    fields.push({
                        html: '<br><h3>' + _('bxsender_mailing_queue') + '</h3>',
                        colspan: 2
                    })
                }

                fields.push({
                    html: _('bxsender_mailing_' + field),
                    cellCls: 'bxsender_mailing_report_label'
                })

                switch (field) {
                    case 'id':
                        value = '<a target="_blank" href="../assets/components/bxsender/action/openbrowser/index.php?mailing_id=' + value + '">' + _('bxsender_mailing_btn_open_message') + '</a> <i class="icon icon-external-link"></i>'
                        break
                    case 'shipping_status':
                        value = _('bxsender_mailing_shipping_status_' + value)
                        break
                    default:

                        switch (typeof value) {
                            case 'boolean':
                                value = value ? _('yes') : _('no')
                                break
                            default:
                                value = value.toString()
                                break
                        }
                        break
                }
                fields.push({
                    html: value,
                })
            }
        }

        return [
            {
                layout: {
                    type: 'table',
                    columns: 2
                },
                defaults: {
                    bodyStyle: 'padding:10px 5px'
                },
                cls: 'bxsender_mailing_report',
                items: fields,
            }
        ]
    }

})
Ext.reg('bxsender-window-mailing-update', bxSender.window.UpdateMailing)

bxSender.window.Tips = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('bxsender_mailing_window_tips'),
        width: 600,
        height: 400,
        autoHeight: false,
        fields: [
            {
                style: {margin: '15px 0 0 0'},
                xtype: 'bxsender-description',
                id: config.id + '-message_desc',
                html: _('bxsender_mailing_message_desc'),
            }
        ], buttons: [
            {
                text: config.cancelBtnText || _('cancel')
                , scope: this
                , handler: function () { config.closeAction !== 'close' ? this.hide() : this.close() }
            }
        ]
    })
    bxSender.window.Tips.superclass.constructor.call(this, config) // Магия
}
Ext.extend(bxSender.window.Tips, MODx.Window)
Ext.reg('bxsender-window-names', bxSender.window.Tips)

/* REPORT */
bxSender.window.ReportMailing = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('bxsender_window_mailing_report'),
        width: 900,
        height: 150,
        minHeight: 150,
        baseParams: {
            action: 'mgr/sending/mailing/update',
        }
    })
    bxSender.window.ReportMailing.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.window.ReportMailing, bxSender.window.CreateMailing, {
    getFields: function (config) {
        return [
            {
                xtype: 'modx-tabs',
                border: true,
                stateful: true,
                stateId: 'bxsender-window-mailing-update-report',
                stateEvents: ['tabchange'],
                id: 'bxsender-window-mailing-update-report',
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())}
                },
                cls: 'main-wrapper',
                deferredRender: false,
                items: [
                    {
                        title: _('bxsender_mailing_tab_message'),
                        layout: 'form',
                        hideMode: 'offsets',
                        border: false,
                        autoHeight: true,
                        items: this.getFieldsMessage(config),
                    },
                    {
                        title: _('bxsender_mailing_tab_report'),
                        id: 'bxsender-window-mailing-update-tab-report',
                        layout: 'form',
                        hideMode: 'offsets',
                        border: false,
                        autoHeight: true,
                        items: this.getFieldsReport(config)
                    }
                ]
            }]
    }

    , getFieldsReport: function (config) {

        var fields = []

        var allowedFields = ['createdon', 'updatedon', 'service', 'shipping_status', 'start_by_time', 'start_by_timedon', 'utm', 'utm_campaign', 'utm_medium', 'utm_source', 'queue_count', 'id']

        var utm = config.record.object['utm']
        var start_by_time = config.record.object['start_by_time']

        var value, field = ''
        for (var i in allowedFields) {
            field = allowedFields[i]

            if (config.record.object[field] !== undefined) {
                value = config.record.object[field]

                if (!utm) {
                    if (field === 'utm_campaign' || field === 'utm_medium' || field === 'utm_source') {
                        continue
                    }
                }
                if (!start_by_time) {
                    if (field === 'start_by_timedon') {
                        continue
                    }
                }

                if (field === 'queue_count') {
                    fields.push({
                        html: '<br><h3>' + _('bxsender_mailing_queue') + '</h3>',
                        colspan: 2
                    })
                }

                fields.push({
                    html: _('bxsender_mailing_' + field),
                    cellCls: 'bxsender_mailing_report_label'
                })

                switch (field) {
                    case 'id':
                        value = '<a target="_blank" href="../assets/components/bxsender/action/openbrowser/index.php?mailing_id=' + value + '">' + _('bxsender_mailing_btn_open_message') + '</a> <i class="icon icon-external-link"></i>'
                        break
                    case 'shipping_status':
                        value = _('bxsender_mailing_shipping_status_' + value)
                        break
                    default:

                        switch (typeof value) {
                            case 'boolean':
                                value = value ? _('yes') : _('no')
                                break
                            default:
                                value = value.toString()
                                break
                        }
                        break
                }
                fields.push({
                    html: value,
                })
            }
        }

        return [
            {
                layout: {
                    type: 'table',
                    columns: 2
                },
                defaults: {
                    bodyStyle: 'padding:10px 5px'
                },
                cls: 'bxsender_mailing_report',
                items: fields,
            }
        ]
    }

})
Ext.reg('bxsender-window-mailing-update-report', bxSender.window.ReportMailing)

