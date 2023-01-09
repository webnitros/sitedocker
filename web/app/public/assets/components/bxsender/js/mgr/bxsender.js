var bxSender = function (config) {
    config = config || {}
    bxSender.superclass.constructor.call(this, config)
}
Ext.extend(bxSender, Ext.Component, {
    page: {}, window: {}, form: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, keymap: {}, plugin: {},
})
Ext.reg('bxSender', bxSender)
bxSender = new bxSender()