bxSender.grid.Segments = function (config) {
    config = config || {}
    if (!config.id) {
        config.package = 'bxsender'
        config.namegrid = 'segment'
        config.id = 'bxsender-grid-segments'
        config.processor = 'mgr/subscription/segment/'
    }

    Ext.applyIf(config, {
        enableDragDrop: true,
        ddGroup: 'bxsender-subscription-segment',
        ddAction: 'mgr/subscription/segment/sort',
        baseParams: {
            sort: 'rank',
            dir: 'asc',
        }
    })
    bxSender.grid.Segments.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.Segments, bxSender.grid.Default, {

    getFields: function () {
        return [
            'id', 'name', 'description', 'active', 'rank','allow_subscription', 'subscribers', 'actions'
        ]
    },

    getColumns: function () {
        return [
            {header: _('bxsender_segment_id'), sortable: true, dataIndex: 'id', width: 50}
            , {header: _('bxsender_segment_rank'), sortable: true, dataIndex: 'rank', width: 50}
            , {header: _('bxsender_segment_name'), sortable: true, dataIndex: 'name', width: 100}
            , {header: _('bxsender_segment_description'), sortable: true, dataIndex: 'description', width: 100}
            , {header: _('bxsender_segment_allow_subscription'), sortable: true, dataIndex: 'allow_subscription', width: 50,
                renderer: bxSender.utils.renderBoolean}
            , {header: _('bxsender_segment_subscribers'), sortable: true, dataIndex: 'subscribers', width: 75}
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
            text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('bxsender_segment_btn_create')
            , handler: this.create
            , scope: this
        },
            '->',
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    copy: function (grid, row, e) {
        this.processors.confirm('copy', 'segment_copy', {ids: this.menu.record.id})
    },

})
Ext.reg('bxsender-grid-segments', bxSender.grid.Segments)