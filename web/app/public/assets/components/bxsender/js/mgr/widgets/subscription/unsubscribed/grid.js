bxSender.grid.unSubscribed = function (config) {
    config = config || {}
    if (!config.id) {
        config.package = 'bxsender'
        config.namegrid = 'unsubscribed'
        config.id = 'bxsender-grid-unsubscribeds'
        config.processor = 'mgr/subscription/unsubscribed/'
    }

    Ext.applyIf(config, {
        enableDragDrop: false,
    })
    bxSender.grid.unSubscribed.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.unSubscribed, bxSender.grid.Default, {

    getFields: function () {
        return [
            'id', 'email', 'user_id','createdon', 'actions'
        ]
    },
    getColumns: function () {
        return [
            {header: _('bxsender_id'), sortable: true, dataIndex: 'id', width: 50}
            , {header: _('bxsender_unsubscribed_subscriber_email'), sortable: true, dataIndex: 'email', width: 100,renderer: bxSender.utils.renderEmail}
            , {header: _('bxsender_unsubscribed_createdon'), sortable: true, dataIndex: 'createdon', width: 75,renderer: bxSender.utils.formatDate}
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
        return [{
            text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('bxsender_unsubscribed_btn_create')
            , handler: this.create
            , scope: this
        },
            '->',
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    renderEmail: function (value, props, row) {
        if (row.data.user_id == 0) {
            return _('bxsender_unsubscribed_empty_subscribe')
        } else {
            return '<a target="_blank" href="/manager/?a=security/user/update&id=' + row.data.user_id + '" style="color:#428bca;">' + value + '</a>'
        }
    },

    getListeners: function () {
        return {
            beforerender: function () {
                this.actionsGrid('beforerender')
            },
            rowDblClick: function (grid, rowIndex, e) {
            }
        }
    }



})
Ext.reg('bxsender-grid-unsubscribeds', bxSender.grid.unSubscribed)