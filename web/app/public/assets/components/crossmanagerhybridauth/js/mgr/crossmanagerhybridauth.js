var crossManagerHybridauth = function (config) {
    config = config || {};
    crossManagerHybridauth.superclass.constructor.call(this, config);
};
Ext.extend(crossManagerHybridauth, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, buttons: {}
});
Ext.reg('crossmanagerhybridauth', crossManagerHybridauth);

crossManagerHybridauth = new crossManagerHybridauth();