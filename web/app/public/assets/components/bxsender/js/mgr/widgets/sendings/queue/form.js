bxSender.panel.QueueForm = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'bxsender-form-queue'
    }

    // Требовать обновления графика или нет
    this.isDirtyChart = true

    Ext.apply(config, {
        layout: 'form',
        cls: 'main-wrapper',
        defaults: {msgTarget: 'under', border: false},
        anchor: '100% 100%',
        border: false,
        items: this.getFields(config),
        listeners: this.getListeners(config),
        //buttons: this.getButtons(config),
        keys: this.getKeys(config),
    })
    bxSender.panel.QueueForm.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.panel.QueueForm, MODx.FormPanel, {

    grid: null,

    getFields: function (config) {
        return [{
            layout: 'column',
            items: [{
                columnWidth: '600px',
                width: 600,
                style: '',
                layout: 'form',
                defaults: {anchor: '100%', hideLabel: true},
                items: this.getLeftFields(config),
            }/*, {
                columnWidth: .37,
                layout: 'form',
                defaults: {anchor: '100%', hideLabel: true},
                items: this.getCenterFields(config),
            }*/, {
                columnWidth: .3,
                layout: 'form',
                style: 'margin-top: 22px;',
                defaults: {anchor: '100%', hideLabel: true},
                items: this.getRightFields(config),
            }],
        }]
    },

    getLeftFields: function (config) {
        return [{
            xtype: 'bxsender-panel-chart',
            id: 'bxsender-panel-chart'
        }]
    },

    getRightFields: function (config) {

        return [
            {
                xtype: 'bxsender-combo-state',
                id: config.id + '-filter_state',
                fieldLabel: _('bxsender_queue_form_state_label'),
                emptyText: _('bxsender_queue_form_state_empty'),
                name: 'state',
                listeners: {
                    select: {
                        fn: function () {
                            this.fireEvent('change')
                        }, scope: this
                    },
                    //select: {fn: this.filterStatistic, scope: this}
                }
            },
            {
                xtype: 'bxsender-combo-mailing',
                id: config.id + '-mailing',
                fieldLabel: _('bxsender_queue_form_mailing_label'),
                emptyText: _('bxsender_queue_form_empty_mailing'),
                name: 'mailing',
                listeners: {
                    select: {
                        fn: function () {
                            this.fireEvent('change')
                        }, scope: this
                    },
                }
            },
            /*{
                xtype: 'bxsender-combo-segment',
                id: config.id + '-filter_segment',
                fieldLabel: 'Тема',
                emptyText: 'Выберите тему',
                name: 'segment',
                listeners: {
                    select: {
                        fn: function () {
                            this.fireEvent('change')
                        }, scope: this
                    },
                }
            },*/ {
                layout: 'column'
                , border: false
                , anchor: '100%'
                , items: [{
                    columnWidth: .5
                    , layout: 'form'
                    , defaults: {msgTarget: 'under'}
                    , border: false
                    , items: [
                        {
                            xtype: 'datefield', hideLabel: true,
                            id: config.id + '-begin',
                            emptyText: _('bxsender_chart_date_start'),
                            name: 'date_start',
                            anchor: '100%',
                            format: MODx.config['manager_date_format'] || 'Y-m-d',
                            listeners: {
                                select: {
                                    fn: function () {
                                        this.fireEvent('change')
                                    }, scope: this
                                },
                            },
                        }
                    ]
                }, {
                    columnWidth: .5
                    , layout: 'form'
                    , defaults: {msgTarget: 'under'}
                    , border: false
                    , items: [
                        {
                            xtype: 'datefield',
                            id: config.id + '-end', hideLabel: true,
                            emptyText: _('bxsender_chart_date_end'),
                            name: 'date_end',
                            anchor: '100%',
                            format: MODx.config['manager_date_format'] || 'Y-m-d',
                            listeners: {
                                select: {
                                    fn: function () {
                                        this.fireEvent('change')
                                    }, scope: this
                                },
                            },
                        }
                    ]
                }]
            }, {
                xtype: 'textfield',
                id: config.id + '-search',
                emptyText: _('bxsender_chart_form_search'),
                name: 'query',
            }, {
                xtype: 'hidden',
                id: config.id + '-status',
                emptyText: _('bxsender_chart_form_status'),
                name: 'status',
            },
            {
                xtype: 'displayfield',
                id: config.id + '-queue-info',
                html: String.format('\
                <table>\
                    <tr class="top">\
                        <td><span id="bxsender-queue-total-count">0</span><br>{0}</td>\
                    </tr>\
                </table>',
                    _('bxsender_queue_total_count')
                ),
            }, {
                layout: 'column'
                , border: false
                , anchor: '100%'
                , style: {margin: '15px 0px 0px 0px'}
                , items: [{
                    layout: 'form'
                    , defaults: {msgTarget: 'under'}
                    , border: false
                    , items: [
                        {
                            xtype: 'button',
                            id: config.id + '-button-submit',
                            text: '<i class="icon icon-check"></i> ' + _('bxsender_queue_form_submit'),
                            cls: 'primary-button',
                            scope: this,
                            handler: this.submit,
                        }
                    ]
                }, {
                    layout: 'form'
                    , defaults: {msgTarget: 'under'}
                    , border: false
                    , items: [
                        {
                            xtype: 'button',
                            text: '<i class="icon icon-times"></i> ' + _('bxsender_queue_form_reset'),
                            handler: this.reset,
                            scope: this,
                        }
                    ]
                }]
            }
        ]
    },

    getCenterFields: function () {
        return []
    },
    getListeners: function () {
        return {
            beforerender: function () {
                this.grid = Ext.getCmp('bxsender-grid-queues')
                var store = this.grid.getStore()
                var form = this
                store.on('load', function (res) {
                    form.updateInfo(res.reader['jsonData'])
                })
            },
            afterrender: function () {
                var form = this
                window.setTimeout(function () {
                    form.on('resize', function () {
                        form.updateInfo()
                    })
                }, 100)
            },
            change: function () {
                this.submit()
            },
        }
    },
    /*getButtons: function () {
        return [{
            text: '<i class="icon icon-times"></i> ' + _('bxsender_queue_form_reset'),
            handler: this.reset,
            scope: this,
            iconCls: 'x-btn-small',
        }, {
            text: '<i class="icon icon-check"></i> ' + _('bxsender_queue_form_submit'),
            handler: this.submit,
            scope: this,
            cls: 'primary-button',
            iconCls: 'x-btn-small',
        }];
    },*/

    getKeys: function () {
        return [{
            key: Ext.EventObject.ENTER,
            fn: function () {
                this.submit()
            },
            scope: this
        }]
    },

    submit: function () {
        var store = this.grid.getStore()
        var form = this.getForm()
        var values = form.getFieldValues()
        for (var i in values) {
            if (i != undefined && values.hasOwnProperty(i)) {
                if (i != 'undefined') {
                    store.baseParams[i] = values[i]
                }
            }
        }
        this.refresh()
    },

    reset: function () {
        var store = this.grid.getStore()
        var form = this.getForm()

        form.items.each(function (f) {
            f.reset()
        })

        var values = form.getValues()
        for (var i in values) {
            if (values.hasOwnProperty(i)) {
                store.baseParams[i] = ''
            }
        }
        this.refresh()

        // Сброс классов
        var chart = Ext.getCmp('bxsender-panel-chart')
        chart.resetSelected()

    },

    refresh: function () {
        this.grid.getBottomToolbar().changePage(1)
    },

    updateInfo: function (data) {

        if (data !== undefined) {
            if (this.isDirtyChart) {

                if (data.сhart !== undefined) {
                    bxSender.сhart = data.сhart
                    var chart = Ext.getCmp('bxsender-panel-chart')
                    chart.Chart('state', 'statePercent', _('bxsender_state'))
                }
            }

            this.isDirtyChart = true
            var elem = Ext.get('bxsender-queue-total-count')
            elem.update(data.total)
        }
    },

    focusFirstField: function () {
    },

})
Ext.reg('bxsender-form-queue', bxSender.panel.QueueForm)