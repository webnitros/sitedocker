bxSender.grid.SegmentMembers = function (config) {
    config = config || {}

    if (!config.id) {
        config.id = 'bxsender-grid-segment-members'
        config.package = 'bxsender'
        config.namegrid = 'segmentmembers'
        config.processor = 'mgr/subscription/segment/members/'
    }
    Ext.applyIf(config, {
        baseParams: {
            sort: 'id',
            dir: 'asc',
            segment: config.record.object.id,
        },
        pageSize: 5,
        multi_select: true,
    })
    bxSender.grid.SegmentMembers.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.grid.SegmentMembers, bxSender.grid.Default, {

    getFields: function () {
        return ['id', 'email', 'fullname', 'active', 'actions']
    },

    getColumns: function () {
        return [
            {header: _('bxsender_subscriber_id'), dataIndex: 'id', sortable: true, width: 40},
            {header: _('bxsender_subscriber_email'), dataIndex: 'email', sortable: true, width: 75},
            {header: _('bxsender_subscriber_fullname'), dataIndex: 'fullname', width: 75,hidden: true},
            {
                header: _('bxsender_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 35,
                renderer: bxSender.utils.renderActions
            }
        ]
    },

    getTopBar: function (config) {
        return [
            '->',
            this.getSearchField(config)
        ]
    },

    getListeners: function () {
        return []
    },
    remove: function (grid, row, e) {},

    segmentsAction: function (method) {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }
        MODx.Ajax.request({
            url: bxSender.config.connector_url,
            params: {
                action: 'mgr/subscription/segment/members/multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        //noinspection JSUnresolvedFunction
                        this.refresh()
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message)
                    }, scope: this
                },
            }
        })
    },

    enableSubscriber: function () {
        this.segmentsAction('enable')
    },

    disableSubscriber: function () {
        this.segmentsAction('disable')
    },

    _getSelectedIds: function () {
        var ids = []
        var selected = this.getSelectionModel().getSelections()

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue
            }
            ids.push({
                segment_id: this.config.record.object.id,
                subscriber_id: selected[i]['id'],
            })
        }

        return ids
    },

})
Ext.reg('bxsender-grid-segment-members', bxSender.grid.SegmentMembers)