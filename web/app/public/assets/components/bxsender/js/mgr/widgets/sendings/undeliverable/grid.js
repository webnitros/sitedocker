bxSender.grid.unDeliverable = function (config) {
    config = config || {}
    if (!config.id) {
        config.package = 'bxsender'
        config.namegrid = 'undeliverable'
        config.id = 'bxsender-grid-undeliverables'
        config.processor = 'mgr/sending/undeliverable/'
    }

    Ext.applyIf(config, {
        enableDragDrop: false,
    })
    bxSender.grid.unDeliverable.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.unDeliverable, bxSender.grid.Default, {

    getFields: function () {
        return [
            'id', 'returnpath_id',  'email', 'subject','cat','type','action','status','createdon', 'actions'
        ]
    },
    getColumns: function () {
        return [
            {header: _('bxsender_id'), sortable: true, dataIndex: 'id', width: 50}

            , {header: _('bxsender_undeliverable_email'), sortable: true, dataIndex: 'email', width: 100}
            , {header: _('bxsender_undeliverable_subject'), sortable: true, dataIndex: 'subject', width: 100}
            , {header: _('bxsender_undeliverable_cat'), sortable: true, dataIndex: 'cat', width: 50,hidden: true}
            , {header: _('bxsender_undeliverable_type'), sortable: true, dataIndex: 'type', width: 50,hidden: true}
            , {header: _('bxsender_undeliverable_action'), sortable: true, dataIndex: 'action', width: 50,hidden: true}
            , {header: _('bxsender_undeliverable_status'), sortable: true, dataIndex: 'status', width: 50}
            ,{header: _('bxsender_undeliverable_returnpath_id'), sortable: true, dataIndex: 'returnpath_id', width: 50,hidden: true}
            , {header: _('bxsender_undeliverable_createdon'), sortable: true, dataIndex: 'createdon', width: 50,renderer: bxSender.utils.formatDate,}
            , {
                header: _('bxsender_actions'),
                dataIndex: 'actions',
                width: 75,
                renderer: bxSender.utils.renderActions,
                id: 'actions'
            }
        ]
    },

    check: function () {
        this.processors.confirm('check', 'undeliverable_check', {})
    },

    getTopBar: function (config) {
        return [
            {
                text: '<i class="' + (MODx.modx23 ? 'icon icon-download' : 'fa fa-download') + '"></i> ' + _('bxsender_undeliverable_btn_check')
                , handler: this.check
                , scope: this
            },
            '->',
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
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
Ext.reg('bxsender-grid-undeliverables', bxSender.grid.unDeliverable)