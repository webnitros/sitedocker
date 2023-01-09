Ext.namespace('bxSender.progressbar');

bxSender.progressbar.Default = function (config) {
    config = config || {}

    //baseParams
    Ext.applyIf(config, {
        package: 'bxsender',
    })
    bxSender.progressbar.Default.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.progressbar.Default, Ext.ProgressBar, {
    getFields: function () {
        return [
            'id', 'actions'
        ]
    },
})
Ext.reg('bxsender-progressbar-default', bxSender.progressbar.Default)