bxSender.grid.Subscriber = function (config) {
    config = config || {}
    if (!config.id) {
        config.package = 'bxsender'
        config.namegrid = 'subscriber'
        config.id = 'bxsender-grid-subscriber'
        config.processor = 'mgr/subscription/subscriber/'
    }
    config.multi_select = true
    Ext.applyIf(config, {
        enableDragDrop: false,
    })
    bxSender.grid.Subscriber.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.Subscriber, bxSender.grid.Default, {
    windows: {
        create: null,
        update: null,
        Loader: null,
    },

    getFields: function () {
        return [
            'id', 'segment_id', 'email', 'user_id', 'hash', 'fullname', 'state', 'opens', 'undeliverable','confirmed', 'unsubscribed', 'createdon', 'segments_count', 'updatedon', 'active', 'actions'
        ]
    },

    getColumns: function () {
        return [
            {header: _('bxsender_subscriber_id'), sortable: true, dataIndex: 'id', width: 30}
            , {
                header: _('bxsender_subscriber_email'),
                sortable: true,
                dataIndex: 'email',
                renderer: bxSender.utils.renderEmail,
                width: 75
            }
            , {
                header: _('bxsender_subscriber_fullname'),
                sortable: true,
                dataIndex: 'fullname',
                width: 60,
                renderer: this.renderFullname
            }
            , {header: _('bxsender_subscriber_segments_count'), sortable: false, dataIndex: 'segments_count', width: 60}
            , {header: _('bxsender_subscriber_state'), sortable: true, dataIndex: 'state', width: 60}
            , {
                header: _('bxsender_subscriber_createdon'),
                sortable: true,
                dataIndex: 'createdon',
                renderer: bxSender.utils.formatDate,
                width: 60,
                hidden: true
            }
            , {
                header: _('bxsender_subscriber_updatedon'),
                sortable: true,
                dataIndex: 'updatedon',
                renderer: bxSender.utils.formatDate,
                width: 60,
                hidden: true
            }

            , {
                header: _('bxsender_subscriber_confirmed'),
                sortable: true,
                dataIndex: 'confirmed',
                renderer: bxSender.utils.renderBoolean,
                width: 50,
                hidden: true
            }

            , {
                header: _('bxsender_subscriber_active'),
                sortable: true,
                dataIndex: 'active',
                renderer: bxSender.utils.renderBoolean,
                width: 50,
                hidden: true
            }
            , {
                header: _('bxsender_actions'),
                dataIndex: 'actions',
                width: 75,
                renderer: bxSender.utils.renderActions,
                id: 'actions'
            }
        ]
    },

    getTopBar: function (config) {
        return [
            {
                text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('bxsender_subscriber_btn_create')
                , handler: this.create
                , scope: this
            },

            {
                text: _('bxsender_subscriber_mass_actions'),
                menu: [



                    {
                        text: '<i class="x-menu-item-icon icon icon-plus"></i> ' + _('bxsender_subscriber_btn_bulk_add_addresses')
                        , handler: this.bulkAddAddresses
                        , scope: this
                    },
                    {
                        text: '<i class="x-menu-item-icon icon icon-plus"></i> ' + _('bxsender_subscriber_import_csv_btn')
                        , handler: this.importCSV
                        , scope: this
                    },
                    '-',  {
                        text        : '<i class="x-menu-item-icon icon icon-times"></i>' + _('bxsender_subscriber_remove_unactivesubscribe'),
                        handler     : this.removeUnConfirmed,
                        scope       : this
                    }
                ]
            },
            {
                xtype: 'bxsender-combo-segment',
                id: 'bxsender-combo-subscriber-segment',
                fieldLabel: _('bxsender_action_combo_sort'),
                emptyText: _('bxsender_action_combo_sort'),
                width: 250,
                listeners: {
                    select: {fn: this.filterSegment, scope: this}
                }
            },
            '->',
            this.getActiveField(config),
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    // removeUnActiveSubscribe
    removeUnConfirmed: function (grid, row, e) {
        var ids = bxSender.processors._getSelectedIds(this)
        this.processors.confirm('removeunconfirmed', 'subscriber_removeunconfirmed', {ids: ids})
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = ''
        this.getStore().baseParams.segment = ''

        var start = Ext.getCmp('bxsender-combo-subscriber-segment')
        start.setValue('')

        this.getBottomToolbar().changePage(1)
    },

    bulkAddAddresses: function (btn, e) {

        var $this = this
        if ($this.windows.Loader) {
            $this.windows.Loader.destroy()
            $this.windows.Loader = null
        }

        if (!$this.windows.Loader) {
            $this.windows.Loader = MODx.load({
                xtype: 'bxsender-window-subscriber-member-add'
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh()
                        }, scope: this
                    },
                    'hide': {
                        fn: function () {
                            $this.windows.Loader.destroy()
                            $this.windows.Loader = null
                        }, scope: this
                    }
                }
            })
        }
        $this.windows.Loader.fp.getForm().reset()
        $this.windows.Loader.show(e.target)

    },

    importCSV: function (btn, e) {

        var $this = this
        if ($this.windows.Loader) {
            $this.windows.Loader.destroy()
            $this.windows.Loader = null
        }

        if (!$this.windows.Loader) {
            $this.windows.Loader = MODx.load({
                xtype: 'bxsender-window-subscriber-import'
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh()
                        }, scope: this
                    },
                    'hide': {
                        fn: function () {
                            $this.windows.Loader.destroy()
                            $this.windows.Loader = null
                        }, scope: this
                    }
                }
            })
        }

        // value: 'email,fullname',
        $this.windows.Loader.fp.getForm().reset()
        $this.windows.Loader.fp.getForm().setValues({fields: 'email,fullname', offset: 0})
        $this.windows.Loader.show(e.target)

    },

    renderFullname: function (value) {
        if (value === '') {
            return '<span class="bxsender_noname">' + _('bxsender_subscriber_none_name') + '</span>'
        }
        return value
    },

    // Сортировка по теме
    filterSegment: function (combo, segment, e) {
        this.getStore().baseParams.segment = segment.id
        this.getBottomToolbar().changePage(1)
    },

})
Ext.reg('bxsender-grid-subscriber', bxSender.grid.Subscriber)