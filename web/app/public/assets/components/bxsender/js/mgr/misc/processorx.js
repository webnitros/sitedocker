Ext.namespace('bxSender.processors');

bxSender.processors.confirm = function ($this, action, lexicon, params) {
    bxSender.utils.onAjax($this.getEl())
    var defaultParams = {
        action: action
    }

    if (params) {
        defaultParams = params
        defaultParams.action = action
    }

    MODx.msg.confirm({
        title: _('bxsender_' + lexicon)
        , text: _('bxsender_' + lexicon + '_confirm')
        , url: bxSender.config.connector_url
        , params: defaultParams,
        listeners: {
            success: {
                fn: function () {
                    $this.refresh()
                }, scope: $this
            }
        }
    })

}

bxSender.processors.one = function ($this, action, btn, e, row) {
    var ids = bxSender.processors._getSelectedIds($this)
    if (!ids) {
        return
    }

    bxSender.utils.onAjax($this.getEl())
    MODx.Ajax.request({
        url: bxSender.config.connector_url
        , params: {
            action: action
            , ids: ids.join(',')
        }
        , listeners: {
            success: {
                fn: function (r) {
                    $this.refresh()
                }, scope: $this
            },
            failure: {
                fn: function (r) {

                    /*var data = r.data;
                    var str = '';
                    for (var p in data) {
                      if (data.hasOwnProperty(p)) {
                        var id = data[p]['id']
                        var msg = data[p]['msg']
                        str += '' + id + '::' + msg + '';
                      }
                    }
                    r.message += '<br>' + str;*/

                    return true
                }
            }
        }
    })
}

bxSender.processors.multiple = function ($this, action, btn, e, row) {
    var ids = bxSender.processors._getSelectedIds($this)
    if (!ids) {
        return
    }

    bxSender.utils.onAjax($this.getEl())
    MODx.Ajax.request({
        url: bxSender.config.connector_url
        , params: {
            action: $this.processor + 'multiple',
            method: action,
            ids: Ext.util.JSON.encode(ids)
        }
        , listeners: {
            success: {
                fn: function (r) {
                    $this.refresh()
                }, scope: $this
            },
            failure: {
                fn: function (r) {

                    /*var data = r.data;
                    var str = '';
                    for (var p in data) {
                      if (data.hasOwnProperty(p)) {
                        var id = data[p]['id']
                        var msg = data[p]['msg']
                        str += '' + id + '::' + msg + '';
                      }
                    }
                    r.message += '<br>' + str;*/

                    return true
                }
            }
        }
    })
}

bxSender.processors.message = function ($this, action, btn, e, row) {

    if (typeof(row) !== 'undefined') {
        $this.menu.record = row.data
    }
    var id = $this.menu.record.id
    bxSender.utils.onAjax($this.getEl())
    MODx.Ajax.request({
        url: $this.config.url
        , params: {
            action: action
            , id: id
        }
        , listeners: {
            success: {
                fn: function (r) {

                    var id = r.object.id
                    var subject = r.object.email_subject
                    var output = r.object.output

                    var win = new Ext.Window({
                        id: 'bxsender_message_window' + id
                        , title: subject
                        , width: 700
                        , maxWidth: 700
                        , maxHeight: 450
                        , height: 450
                        , layout: 'fit'
                        , html: output,
                    })
                    win.show()

                }, scope: $this
            }
        }
    })
}

bxSender.processors._getSelectedIds = function ($this) {
    var ids = []
    var selected = $this.getSelectionModel().getSelections()

    for (var i in selected) {
        if (!selected.hasOwnProperty(i)) {
            continue
        }
        ids.push(selected[i]['id'])
    }
    return ids
}

bxSender.processors.extend = function ($this) {

    Ext.extend($this, MODx.grid.Grid, {

        windows: {}
        , getMenu: function (grid, rowIndex) {
            var row = grid.getStore().getAt(rowIndex)
            var menu = bxSender.utils.getMenu(row.data.actions, this)
            this.addContextMenuItem(menu)
        }

        , onClick: function (e) {
            var elem = e.getTarget()
            if (elem.nodeName === 'BUTTON') {
                var row = this.getSelectionModel().getSelected()
                if (typeof(row) !== 'undefined') {
                    var type = elem.getAttribute('type')
                    var processor = elem.getAttribute('data-processor')
                    var action = elem.getAttribute('data-action')

                    if (type === 'menu') {
                        var ri = this.getStore().find('id', row.id)

                        return this._showMenu(this, ri, e)
                    }
                    else {
                        this.menu.record = row.data
                        if (this[type] !== undefined) {
                            return this[type](this, e)
                        } else {
                            return this.processors[processor](action, this, e, row)
                        }
                    }
                }
            }
            return this.processEvent('click', e)
        }
        , processor: function (elem, processor, $this, e, row) {
            this.processors[elem.options.processor](elem.options.action, $this, e, row)
        },

        _doSearch: function (tf, nv, ov) {
            this.getStore().baseParams.query = tf.getValue()
            this.getBottomToolbar().changePage(1)
            this.refresh()

        },

        _clearSearch: function (btn, e) {
            Ext.getCmp(this.config.id + '-search-field').setValue('')
            this.getStore().baseParams.query = ''
            this.getBottomToolbar().changePage(1)
            this.refresh()
        },
    })
}


