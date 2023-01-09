bxSender.grid.SubscriberMembers = function (config) {
    config = config || {};

    if (!config.id) {
        config.id = 'bxsender-grid-subscriber-members'
        config.package = 'bxsender'
        config.namegrid = 'subscribermembers'
        config.processor = 'mgr/subscription/subscriber/members/'
    }
    Ext.applyIf(config, {
        baseParams: {
            sort: 'rank',
            dir: 'asc',
            subscriber: config.record.object.id,
        },
        pageSize: 5,
        multi_select: true,
    });
    bxSender.grid.SubscriberMembers.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.grid.SubscriberMembers, bxSender.grid.Default, {

    getFields: function () {
        return ['id', 'name', 'rank','active', 'actions'];
    },

    getColumns: function () {
        return [
            {header: _('bxsender_segment_name'), dataIndex: 'name', width: 75},
            //{header: _('bxsender_subscriber_members_subscriber_count'), dataIndex: 'subscriber_count', width: 75},
            {
                header: _('bxsender_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 35,
                renderer: bxSender.utils.renderActions
            }
        ];
    },

    getTopBar: function () {
        return [];
    },

    getListeners: function () {
        return [];
    },
    remove: function (grid, row, e) {},


    segmentsAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: bxSender.config.connector_url,
            params: {
                action: 'mgr/subscription/subscriber/members/multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        //noinspection JSUnresolvedFunction
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    }, scope: this
                },
            }
        })
    },

    enableSegment: function () {
        this.segmentsAction('enable');
    },

    disableSegment: function () {
        this.segmentsAction('disable');
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push({
                subscriber_id: this.config.record.object.id,
                segment_id: selected[i]['id'],
            });
        }

        return ids;
    },

});
Ext.reg('bxsender-grid-subscriber-members', bxSender.grid.SubscriberMembers);