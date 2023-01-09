crossManagerHybridauth.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'crossmanagerhybridauth-panel-home',
            renderTo: 'crossmanagerhybridauth-panel-home-div'
        }]
    });
    crossManagerHybridauth.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(crossManagerHybridauth.page.Home, MODx.Component);
Ext.reg('crossmanagerhybridauth-page-home', crossManagerHybridauth.page.Home);