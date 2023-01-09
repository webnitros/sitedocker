bxSender.grid.MailingRecipients = function (config) {
    config = config || {};

    if (!config.id) {
        config.id = 'bxsender-grid-mailing-recipients'
        config.package = 'bxsender'
        config.namegrid = 'recipients'
        config.processor = 'mgr/sending/mailing/recipients/'
    }
    Ext.applyIf(config, {
        baseParams: {
            sort: 'rank',
            dir: 'asc',
            mailing: config.record.object.id,
        },
        pageSize: 5,
        multi_select: true,
    });
    bxSender.grid.MailingRecipients.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.grid.MailingRecipients, bxSender.grid.Default, {

    getFields: function () {
        return ['id', 'name', 'rank','active','subscriber_count', 'actions'];
    },

    getColumns: function () {
        return [
            {header: _('bxsender_recipients_name'), dataIndex: 'name', width: 75},
            {header: _('bxsender_recipients_subscriber_count'), dataIndex: 'subscriber_count', width: 75},
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
                action: 'mgr/sending/mailing/recipients/multiple',
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
                mailing_id: this.config.record.object.id,
                segment_id: selected[i]['id'],
            });
        }

        return ids;
    },

});
Ext.reg('bxsender-grid-mailing-recipients', bxSender.grid.MailingRecipients);