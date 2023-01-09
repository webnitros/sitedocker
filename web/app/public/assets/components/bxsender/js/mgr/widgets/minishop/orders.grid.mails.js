miniShop2.grid.BxSender = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'minishop2-grid-order-bxsender'
    }
    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/order/getlist',
            order_id: config.order_id,
            type: 'status',
            dir: 'DESC',
            combo: 1
        },
        url: bxSender.config.connector_url,
        cls: 'minishop2-grid',
        multi_select: false,
        stateful: true,
        stateId: config.id,
        pageSize: Math.round(MODx.config['default_per_page'] / 2),
    })
    miniShop2.grid.BxSender.superclass.constructor.call(this, config)
}
Ext.extend(miniShop2.grid.BxSender, miniShop2.grid.Default, {

    getFields: function () {
        return ['id', 'order_id', 'from', 'queue_id', 'status', 'state', 'state_name', 'status_name', 'createdon', 'actions']
    },

    getColumns: function (config) {
        return [
            {header: 'ID', dataIndex: 'id', width: 20, sortable: true, hidden: true},
            {header: 'queue_id', dataIndex: 'queue_id', sortable: true, width: 60, hidden: true},
            {
                header: 'Кому',
                dataIndex: 'from',
                sortable: true,
                width: 80,
                hidden: false,
                renderer: function (value) {
                    return value === 'user' ? 'Покупателю' : 'Менеджеру'
                }
            },
            {header: 'Статус заказа', dataIndex: 'status_name', sortable: true, width: 100},
            {header: 'Состояние отправки', dataIndex: 'state', sortable: true, width: 70},
            {
                header: 'Создано',
                dataIndex: 'createdon',
                sortable: true,
                width: 100,
                renderer: miniShop2.utils.formatDate,
                hidden: false
            },
            {
                header: 'Действия',
                dataIndex: 'actions',
                id: 'actions',
                width: 80,
                renderer: miniShop2.utils.renderActions
            }
        ]
    },

    getTopBar: function () {
        return []
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
        return String.format('<span class="{0}">{1}</span>', color, text)
    },

    showMessage: function (row,e) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.menu.record;
        }
        else if (!this.menu.record) {
            return false;
        }
        var queue_id = this.menu.record.queue_id;
        MODx.Ajax.request({
            url: bxSender.config.connector_url
            , params: {
                action: 'mgr/queue/content',
                id: queue_id
            }
            , listeners: {
                success: {
                    fn: function (r) {

                        console.log(r.object)
                        var id = r.object.id
                        var subject = r.object.email_subject
                        var output = r.object.output

                        console.log(output)
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

                    }, scope: this
                }
            }
        })
    }
})
Ext.reg('minishop2-grid-order-bxsender', miniShop2.grid.BxSender)