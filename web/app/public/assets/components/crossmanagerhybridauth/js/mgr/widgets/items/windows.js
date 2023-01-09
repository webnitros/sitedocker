crossManagerHybridauth.window.CreateItem = function (config) {
    config = config || {}
    config.url = crossManagerHybridauth.config.connector_url

    Ext.applyIf(config, {
        title: _('crossmanagerhybridauth_item_create'),
        width: 600,
        cls: 'crossmanagerhybridauth_windows',
        baseParams: {
            action: 'mgr/item/create',
            resource_id: config.resource_id
        }
    })
    crossManagerHybridauth.window.CreateItem.superclass.constructor.call(this, config)

    this.on('success', function (data) {
        if (data.a.result.object) {
            // Авто запуск при создании новой подписик
            if (data.a.result.object.mode) {
                if (data.a.result.object.mode === 'new') {
                    var grid = Ext.getCmp('crossmanagerhybridauth-grid-items')
                    grid.updateItem(grid, '', {data: data.a.result.object})
                }
            }
        }
    }, this)
}
Ext.extend(crossManagerHybridauth.window.CreateItem, crossManagerHybridauth.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                fieldLabel: _('crossmanagerhybridauth_item_name'),
                name: 'name',
                id: config.id + '-name',
                anchor: '99%',
                allowBlank: false,
            }, {
                xtype: 'textarea',
                fieldLabel: _('crossmanagerhybridauth_item_description'),
                name: 'description',
                id: config.id + '-description',
                height: 150,
                anchor: '99%'
            },  {
                xtype: 'crossmanagerhybridauth-combo-filter-resource',
                fieldLabel: _('crossmanagerhybridauth_item_resource_id'),
                name: 'resource_id',
                id: config.id + '-resource_id',
                height: 150,
                anchor: '99%'
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('crossmanagerhybridauth_item_active'),
                name: 'active',
                id: config.id + '-active',
                checked: true,
            }
        ]


    }
})
Ext.reg('crossmanagerhybridauth-item-window-create', crossManagerHybridauth.window.CreateItem)

crossManagerHybridauth.window.UpdateItem = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('crossmanagerhybridauth_item_update'),
        baseParams: {
            action: 'mgr/item/update',
            resource_id: config.resource_id
        },
    })
    crossManagerHybridauth.window.UpdateItem.superclass.constructor.call(this, config)
}
Ext.extend(crossManagerHybridauth.window.UpdateItem, crossManagerHybridauth.window.CreateItem)
Ext.reg('crossmanagerhybridauth-item-window-update', crossManagerHybridauth.window.UpdateItem)