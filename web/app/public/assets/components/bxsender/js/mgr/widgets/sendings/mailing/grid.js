bxSender.grid.MailingGrid = function (config) {
    config = config || {}
    if (!config.id) {
        config.package = 'bxsender'
        config.namegrid = 'mailing'
        config.id = 'bxsender-grid-mailing'
        config.processor = 'mgr/sending/mailing/'
        config.createValues = {
            utm_source: 'bx_segment',
            utm_medium: 'bx_medium',
            utm_campaign: 'bx_mailing',
            message: bxSender.config.default_message,
            service: 'bxsender',
        }
    }

    Ext.applyIf(config, {
        enableDragDrop: false,
        pageSize: 5,
        baseParams: {
            dir: 'DESC',
            active: true,
        }
    })
    bxSender.grid.MailingGrid.superclass.constructor.call(this, config)
}

Ext.extend(bxSender.grid.MailingGrid, bxSender.grid.Default, {

    getFields: function () {
        return [
            'id', 'subject', 'description', 'active', 'start_mailing', 'service', 'shipping_status','subscribers_count', 'subscribers_sent', 'segment_count', 'message', 'actions'
        ]
    },

    getColumns: function () {
        return [
            {header: _('bxsender_id'), sortable: true, dataIndex: 'id', width: 20}
            , {
                header: _('bxsender_mailing_subject'),
                sortable: true,
                dataIndex: 'subject',
                width: 100,
                renderer: this.renderSubject
            }
            , {
                header: _('bxsender_mailing_shipping_status'),
                sortable: true,
                dataIndex: 'shipping_status',
                width: 60,
                renderer: this.renderSendingProcess
            }
            , {header: _('bxsender_mailing_service'), sortable: true, dataIndex: 'service', width: 70, hidden: true}
            , {
                header: _('bxsender_mailing_subscribers_count'),
                sortable: false,
                dataIndex: 'subscribers_count',
                width: 40
            }, {
                header: _('bxsender_mailing_start_mailing'),
                sortable: true,
                dataIndex: 'start_mailing',
                width: 60,
                hidden: true,
                renderer: bxSender.utils.formatDate
            }
            , {
                header: _('bxsender_mailing_message'),
                sortable: false,
                dataIndex: 'message',
                width: 200,
                renderer: this.renderIframe
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

    renderSendingProcess: function (value, props, row) {
        var data = row.data
        var id = row.id

        var service = data.service
        var shipping_status = data.shipping_status
        var queue_sent = parseInt(data.subscribers_sent)
        var count = parseInt(data.subscribers_count)
        var subscribers_queue = parseInt(data.subscribers_queue)

        var color = '000'
        switch (shipping_status) {
            case 'completed':
                color = '008000'
                break
            case 'process':
                color = 'F6C218'
                break
            case 'draft':
                color = '80314D'
                break
            case 'paused':
                color = '7E0000'
                break
            default:
                break
        }

        var output = '<span class="bxsender_progress_bar_status" style="color: #' + color + '">' + _('bxsender_mailing_shipping_status_' + shipping_status) + '</span>'
        if (service === 'bxsender') {
            switch (shipping_status) {
                case 'completed':
                case 'process':
                    var percent = Math.floor(queue_sent / count * 100)
                    if (percent > 100) {
                        percent = 100;
                    }
                    if (isNaN(percent)) {
                        percent = 0
                    }
                    var motion = percent === 100 ? 1 : '0.' + percent

                    var progressbarId = 'bxsender-mailing-progressbar-' + id;
                    (function () {
                        row.progressbar = new Ext.ProgressBar({
                            renderTo: progressbarId
                            , x: 0
                            , value: motion
                            , text: percent + '%'
                        })
                    }).defer(50)
                    output += '<div id="' + progressbarId + '"></div>'
                    output += '<div class="bxsender_progress_bar_stat">отправлено ' + queue_sent + ' из ' + count + '</div>'
                    break
                default:
                    break
            }
        }
        return '<div class="bxsender_progress_bar">' + output + '</div>'
    },

    renderSubject: function (value, props, row) {
        if (row.data.service !== 'bxsender') {
            return value + '<br><span class="bxsender_noname">' + row.data.service + '</span>'
        }
        return value

    },

    renderIframe: function (value, props, row) {
        if (row.data.service !== 'bxsender') {
            return '<span class="bxsender_noname">' + _('bxsender_mailing_not_used') + '</span>'
        }

        var body = '<!DOCTYPE html><html><head></head><body>' + value + '</body></html>'
        body = body.replace(/"/g, '&quot;')
        var url = MODx.config.site_url + bxSender.config.openbrowserUrl + '?mailing_id=' + row.id;
        var output = '<a target="_blank" href="' +url + '"><div class="bxsender-mailing-wrap"><div class="modx-browser-thumb"><iframe class="bxsender-mailing-iframe" scrolling="no" srcdoc="' + body + '" ></iframe></div></div></a>'
        var linkopen = '<br><a target="_blank" href="'+url + '">' + _('bxsender_mailing_btn_open_message') + '</a>'
        return output + linkopen

    },

    getTopBar: function (config) {
        return [{
            text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('bxsender_mailing_btn_create')
            , handler: this.create
            , scope: this
        },
            '->',
            this.getActiveField(config),
            this.getTotalResults(config),
            this.getSearchField(config)
        ]
    },

    getStatistics: function (grid, row, e) {
        var panel = Ext.getCmp('bxsender-panel-sendings')
        panel.getStatistics()
    },

    // addQueues
    addQueues: function (grid, row, e) {
        var id = bxSender.processors._getSelectedIds(this)
        this.processors.confirm('addqueues', 'mailing_addqueues', {mailing_id: id})
    },

    statusPaused: function (grid, row, e) {
        this.processors.multiple('paused')
    },

    statusProcess: function (grid, row, e) {
        this.processors.multiple('process')
    },

    // copy
    copy: function (grid, row, e) {
        var ids = bxSender.processors._getSelectedIds(this)
        this.processors.confirm('copy', 'mailing_copy', {ids: ids})
    },


    loadXtype: function (def,r) {
        if (r.object) {
           if (r.object.completed) {
               return def+'-report'
           }
        }
  
        return def
    },

})
Ext.reg('bxsender-grid-mailing', bxSender.grid.MailingGrid)