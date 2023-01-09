bxSender.grid.Queues = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'bxsender-grid-queues'
        config.namegrid = 'queues'
        config.processor = 'mgr/queue/'
    }
    config.multi_select = true
    bxSender.grid.Queues.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.Queues, bxSender.grid.Default, {

    getFields: function () {
        return [
            'id', 'segment_id', 'subscriber_id', 'timestamp', 'createdon', 'letter_readon', 'unsubscribed','undeliverable', 'sent', 'unsubscribed', 'unsubscribedon', 'email_to', 'state', 'opens', 'clicks',  'action', 'email_subject', 'email_body', 'createdon', 'updatedon','datesent', 'actions'
        ]
    },
    getColumns: function () {
        return [
            {header: _('bxsender_queue_id'), sortable: true, dataIndex: 'id', width: 30}
            , {header: _('bxsender_queue_email_subject'),sortable: true,dataIndex: 'email_subject',width: 100}
            , {header: _('bxsender_queue_email_to'), sortable: true, dataIndex: 'email_to', width: 75}
            , {header: _('bxsender_queue_state'), sortable: true, dataIndex: 'state', width: 75}

            , {header: _('bxsender_queue_clicks'), sortable: true, dataIndex: 'clicks', width: 60, hidden: false}
            , {header: _('bxsender_queue_opens'), sortable: true, dataIndex: 'opens', width: 60, hidden: false, renderer: bxSender.utils.renderBoolean}
            , {header: _('bxsender_queue_unsubscribed'),sortable: false, dataIndex: 'unsubscribed', width: 40, renderer: bxSender.utils.renderBoolean}
            , {header: _('bxsender_queue_undeliverable'), sortable: false, dataIndex: 'undeliverable', width: 40, renderer: bxSender.utils.renderBoolean}
            , {header: _('bxsender_queue_failure'), sortable: false, dataIndex: 'failure', width: 40, renderer: bxSender.utils.renderBoolean}
            , {header: _('bxsender_queue_createdon'),sortable: true,dataIndex: 'createdon',renderer: bxSender.utils.formatDate,width: 70,hidden: true}
            , {header: _('bxsender_queue_updatedon'), sortable: true,dataIndex: 'updatedon',renderer: bxSender.utils.formatDate, width: 70,hidden: true}
            , {header: _('bxsender_queue_datesent'),sortable: true,dataIndex: 'datesent',renderer: bxSender.utils.formatDate,width: 70,hidden: true}
            , {
                header: _('bxsender_actions'),
                dataIndex: 'actions',
                width: 75,
                renderer: bxSender.utils.renderActions,
                id: 'actions'
            }
        ]
    },
    getListeners: function () {
        return {
            beforerender: function () {
                this.actionsGrid('beforerender')
            }
        }
    },

    getTopBar: function (config) {
        return []
        return [
            /*{
              xtype: 'bxsender-combo-segment',
              width: 300,
              listeners: {
                select: {fn: this.createQueues, scope: this}
              }
            },
            {
              xtype: 'button',
              text: '<i class="' + (MODx.modx23 ? 'icon icon-trash-o' : 'fa fa-trash-o') + '"></i> ' + _('bxsender_btn_remove_all'),
              handler: this.removeAll,
              scope: this
            },*/
            '->',
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    removeAll: function () {
        this.processors.confirm('remove_all', 'queues_remove_all')
    },

    createQueues: function (combo, segment, e) {
        combo.reset()
        this.processors.confirm('action/send_all', 'queues_send_all', {segment_id: segment.id})
    },

    actionSend: function (grid, row, e) {
        this.processors.multiple('action/send');
    },

    actionQuery: function (grid, row, e) {
        this.processors.multiple('action/query');
    },


    showMessage: function (grid, row, e) {
        this.processors.message('get')
    },

    actionContent: function (grid, row, e) {
        this.processors.message('content');
    },

})
Ext.reg('bxsender-grid-queues', bxSender.grid.Queues)