// UPDATE
miniShop2.window.bxSenderUpdateOrder = function (config) {miniShop2.window.bxSenderUpdateOrder.superclass.constructor.call(this, config)}
Ext.extend(miniShop2.window.bxSenderUpdateOrder, miniShop2.window.UpdateOrder, {
    getTabs: function (config) {
        var original = miniShop2.window.UpdateOrder.prototype.getTabs.call(this, config);
        original.push({
            xtype: 'minishop2-grid-order-bxsender',
            title: 'bxSender',
            id: 'bxsender_minishop_tab_' + config.record.id,
            order_id: config.record.id
        })
        return original
    },
})
Ext.reg('minishop2-window-order-update', miniShop2.window.bxSenderUpdateOrder)