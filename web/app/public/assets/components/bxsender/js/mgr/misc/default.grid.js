bxSender.grid.Default = function (config) {
    config = config || {}

    if (typeof(config['multi_select']) !== 'undefined' && config['multi_select'] === true) {
        config.sm = new Ext.grid.CheckboxSelectionModel()
    }

    // Включение перетаскиваниея элементов
    if (config.enableDragDrop && config.ddAction) {
        config.ddGroup = config.package + '-settings-' + config.namegrid
        config.ddAction = config.processor + 'sort'
    }


    var getlist = config.processor + 'getlist'
    if (config.baseParams === undefined) {
        config.baseParams = {
            action: getlist
        }
    } else if (config.baseParams.action === undefined) {
        config.baseParams.action = getlist
    }
    
    if (config.createValues === undefined) {
        config.createValues = {}
    }

    //baseParams
    Ext.applyIf(config, {
        package: 'bxsender',
        stateId: config.id,
        stateful: true, // Сохранять состояние в хранилище
        url: bxSender.config['connector_url'],
        baseParams: {},
        cls: config['cls'] || 'main-wrapper bxsender-grid',
        autoHeight: true,
        paging: true,
        remoteSort: true,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        listeners: this.getListeners(config),
        processors: {
            element: this,
            message: function (action, btn, e, row) {
                bxSender.processors.message(this.element, config.processor + action, btn, e, row)
            },
            one: function (action, btn, e, row) {
                bxSender.processors.one(this.element, config.processor + action, btn, e, row)
            },
            confirm: function (action, lexicon, params) {
                bxSender.processors.confirm(this.element, config.processor + action, lexicon, params)
            },
            multiple: function (action, lexicon, params) {
                bxSender.processors.multiple(this.element, config.processor + action, lexicon, params)
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: -10,
            getRowClass: function (rec) {
                var cls = []
                if (rec.data['published'] !== undefined && rec.data['published'] === false) {
                    cls.push('bxsender-row-unpublished')
                }

                if (rec.data['active'] !== undefined && rec.data['active'] === false) {
                    cls.push('bxsender-row-disabled')
                }

                if (rec.data['active'] !== undefined && rec.data['active'] === 0) {
                    cls.push('bxsender-row-disabled')
                }
                if (rec.data['deleted'] !== undefined && rec.data['deleted'] === true) {
                    cls.push('bxsender-row-deleted')
                }
                if (rec.data['required'] !== undefined && rec.data['required'] === true) {
                    cls.push('bxsender-row-required')
                }
                return cls.join(' ')
            }
        },
    })

    bxSender.grid.Default.superclass.constructor.call(this, config)

    if (config.enableDragDrop && config.ddAction) {
        this.on('render', function (grid) {
            grid._initDD(config)
        })
    }

}
Ext.extend(bxSender.grid.Default, MODx.grid.Grid, {
    windows: {
        create: null,
        update: null,
    },

    getFields: function () {
        return [
            'id', 'actions'
        ]
    },

    getColumns: function () {
        return [{
            header: _('id'),
            dataIndex: 'id',
            width: 35,
            sortable: true,
        }, {
            header: _('bxsender_actions'),
            dataIndex: 'actions',
            renderer: bxSender.utils.renderActions,
            sortable: false,
            width: 75,
            id: 'actions'
        }]
    },

    getTopBar: function (config) {
        return [{
            text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('bxsender_btn_create')
            , handler: this.create
            , scope: this
        },
            '->',
            this.getActiveField(config),
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    getSearchField: function (config) {
        return {
            xtype: 'bxsender-field-search',
            id: 'bxsender-' + config.namegrid + '-field-search',
            width: 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field)
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('')
                        this._clearSearch()
                    }, scope: this
                },
            }
        }
    },

    getActiveField: function (config) {
        return {
            xtype: 'xcheckbox',
            id: 'bxsender-' + config.namegrid + '-field-active',
            name: 'active',
            width: 150,
            boxLabel: _('bxsender_field_active'),
            ctCls: 'tbar-checkbox bxsender-field-active',
            checked: true,
            listeners: {
                check: {fn: this.activeFilter, scope: this}
            }
        }
    },

    getUnDeliverableField: function (config) {
        return {
            xtype: 'xcheckbox',
            id: 'bxsender-' + config.namegrid + '-field-undeliverable',
            name: 'undeliverable',
            width: 150,
            boxLabel: _('bxsender_field_undeliverable'),
            ctCls: 'tbar-checkbox bxsender-field-undeliverable',
            checked: false,
            listeners: {
                check: {fn: this.undeliverableFilter, scope: this}
            }
        }
    },

    getTotalResults: function (config) {
        return {
            xtype: 'displayfield',
            cls: 'bxsender_panel_total',
            id: 'bxsender-panel-' + config.namegrid + '-info-total',
            html: String.format('\
                  <table class="bxsender_panel_info">\
                      <tr class="top">\
                          <td><span id="bxsender-panel-' + config.namegrid + '-info-total">0</span></td>\
                      </tr>\
                  </table>',
                _('bxsender_panel_info_total')
            ),
        }
    },

    getListeners: function () {
        return {
            beforerender: function () {
                this.actionsGrid('beforerender')
            },
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.update(grid, e, row)
            }
        }
    },

    actionsGrid: function (action, field, value) {

        this.grid = this
        if (this.grid) {
            var store = this.grid.getStore()
            switch (action) {
                case 'beforerender':
                    var form = this
                    store.on('load', function (res) {
                        form.updateInfo(res.reader['jsonData'])
                    })
                    break
                default:
                    break
            }
        }
    },

    updateInfo: function (data) {
        var arr = {
            'total': 'total',
        }

        for (var i in arr) {
            if (!arr.hasOwnProperty(i)) {
                continue
            }
            var elem = Ext.get('bxsender-panel-' + this.namegrid + '-info-' + i)
            if (elem) {
                var val = data != undefined
                    ? data[arr[i]]
                    : elem.dom.innerText

                val = String(val)
                elem.update(val)
            }
        }
    },

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds()
        var row = grid.getStore().getAt(rowIndex)
        var menu = bxSender.utils.getMenu(row.data['actions'], this, ids)
        this.addContextMenuItem(menu)
    },

    onClick: function (e) {
        var elem = e.getTarget()
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected()
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action')
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id)
                    return this._showMenu(this, ri, e)
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data
                    return this[action](this, e)
                }
            }
        }
        else if (elem.nodeName == 'A' && elem.href.match(/(\?|\&)a=resource/)) {
            if (e.button == 1 || (e.button == 0 && e.ctrlKey == true)) {
                // Bypass
            }
            else if (elem.target && elem.target == '_blank') {
                // Bypass
            }
            else {
                e.preventDefault()
                MODx.loadPage('', elem.href)
            }
        }
        return this.processEvent('click', e)
    },

    refresh: function () {
        this.getStore().reload()
        if (this.config['enableDragDrop'] === true) {
            this.getSelectionModel().clearSelections(true)
        }
    },

    undeliverableFilter: function (checkbox, checked) {
        this.getStore().baseParams.undeliverable = checked ? 1 : 0
        this.getBottomToolbar().changePage(1)
    },

    activeFilter: function (checkbox, checked) {
        this.getStore().baseParams.active = checked ? 1 : 0
        this.getBottomToolbar().changePage(1)
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue()
        this.getBottomToolbar().changePage(1)
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = ''
        this.getBottomToolbar().changePage(1)
    },

    _getSelectedIds: function () {
        var ids = []
        var selected = this.getSelectionModel().getSelections()

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue
            }
            ids.push(selected[i]['id'])
        }

        return ids
    },

    _initDD: function (config) {
        var grid = this
        var el = grid.getEl()

        new Ext.dd.DropTarget(el, {
            ddGroup: grid.ddGroup,
            notifyDrop: function (dd, e, data) {
                var store = grid.getStore()
                var target = store.getAt(dd.getDragData(e).rowIndex).id
                var sources = []
                if (data.selections.length < 1 || data.selections[0].id === target) {
                    return false
                }
                for (var i in data.selections) {
                    if (!data.selections.hasOwnProperty(i)) {
                        continue
                    }
                    var row = data.selections[i]
                    sources.push(row.id)
                }

                el.mask(_('loading'), 'x-mask-loading')
                MODx.Ajax.request({
                    url: config.url,
                    params: {
                        action: config.ddAction,
                        sources: Ext.util.JSON.encode(sources),
                        target: target,
                    },
                    listeners: {
                        success: {
                            fn: function () {
                                el.unmask()
                                grid.refresh()
                                if (typeof(grid.reloadTree) === 'function') {
                                    sources.push(target)
                                    grid.reloadTree(sources)
                                }
                            }, scope: grid
                        },
                        failure: {
                            fn: function () {
                                el.unmask()
                            }, scope: grid
                        },
                    }
                })
            },
        })
    },

    _loadStore: function () {
        this.store = new Ext.data.JsonStore({
            url: this.config.url,
            baseParams: this.config.baseParams || {action: this.config.action || 'getList'},
            fields: this.config.fields,
            root: 'results',
            totalProperty: 'total',
            remoteSort: this.config.remoteSort || false,
            storeId: this.config.storeId || Ext.id(),
            autoDestroy: true,
            listeners: {
                load: function (store, rows, data) {
                    store.sortInfo = {
                        field: data.params['sort'] || 'id',
                        direction: data.params['dir'] || 'ASC',
                    }
                    Ext.getCmp('modx-content').doLayout()
                }
            }
        })
    },

    // Actions
    copy: function (grid, row, e) {
        this.processors.confirm('copy')
    },

    enable: function (grid, row, e) {
        this.processors.multiple('enable')
    },

    remove: function (grid, row, e) {
        this.processors.multiple('remove')
    },

    disable: function (grid, row, e) {
        this.processors.multiple('disable')
    },

    testing: function (grid, row, e) {
        this.processors.multiple('testing')
    },

    stream: function (grid, row, e) {
        this.processors.multiple('stream')
    },

    // Modal
    create: function (btn, e) {

        if (this.windows.create) {
            this.windows.create.close()
            this.windows.create.destroy()
            this.windows.create = null
        }

        if (!this.windows.create) {
            this.windows.create = MODx.load({
                xtype: this.config.package + '-window-' + this.config.namegrid + '-create'
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh()
                        }, scope: this
                    }
                }
            })
        }

        this.windows.create.fp.getForm().reset()


        if (this.createValues) {
            this.windows.create.fp.getForm().setValues(this.createValues)
        }

        this.windows.create.show(e.target)
    },

    update: function (grid, e, row) {

        if (typeof(row) !== 'undefined') {
            this.menu.record = row.data
        }

        var $this = this
        var id = this.menu.record.id
        MODx.Ajax.request({
            url: bxSender.config.connector_url
            , params: {

                action: $this.config.processor + 'get'
                , id: id
            }
            , listeners: {
                success: {
                    fn: function (r) {

                        if (this.windows.update) {
                            this.windows.update.close()
                            this.windows.update.destroy()
                            this.windows.update = null
                        }

                        this.windows.update = MODx.load({
                            xtype: this.loadXtype($this.config.package + '-window-' + $this.config.namegrid + '-update', r)
                            , record: r
                            , listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                },
                            }
                        })
                        this.windows.update.fp.getForm().reset()
                        this.windows.update.fp.getForm().setValues(r.object)
                        this.windows.update.show(e.target)
                    }, scope: this
                }
            }
        })
    },

    loadXtype: function (def,r) {
        return def
    },
    
    _renderColor: function (value, cell, row) {
        //noinspection CssInvalidPropertyValue
        return row.data['active']
            ? String.format('<span style="color:#{0}">{1}</span>', row.data['color'], value)
            : value
    },

    _renderBoolean: function (value, cell, row) {
        var color, text

        if (value == 0 || value == false || value == undefined) {
            color = 'red'
            text = _('no')
        }
        else {
            color = 'green'
            text = _('yes')
        }

        return row.data['active']
            ? String.format('<span class="{0}">{1}</span>', color, text)
            : text
    }

})
Ext.reg('bxsender-grid-default', bxSender.grid.Default)