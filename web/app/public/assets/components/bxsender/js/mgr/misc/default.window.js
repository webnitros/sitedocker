bxSender.window.Default = function(config) {
  config = config || {};

  Ext.applyIf(config, {
    title: '',
    url: bxSender.config['connector_url'],
    cls: 'modx-window bxsender-window ' || config['cls'],
    width: 600,
    autoHeight: true,
    allowDrop: false,
    record: {},
    baseParams: {},
    fields: this.getFields(config),
    keys: this.getKeys(config),
    buttons: this.getButtons(config),
    listeners: this.getListeners(config),
  });
  bxSender.window.Default.superclass.constructor.call(this, config);

  this.on('hide', function() {
    var w = this;
    window.setTimeout(function() {
      w.close();
    }, 200);
  });
};
Ext.extend(bxSender.window.Default, MODx.Window, {

  getFields: function() {
    return [];
  },
  getButtons: function(config) {
    return [{
      text: config.cancelBtnText || _('cancel'),
      scope: this,
      handler: function() {
        config.closeAction !== 'close'
          ? this.hide()
          : this.close();
      }
    }, {
      text: config.saveBtnText || _('save'),
      cls: 'primary-button',
      scope: this,
      handler: this.submit,
    }];
  },

  getKeys: function() {
    return [{
      key: Ext.EventObject.ENTER,
      shift: true,
      fn: function() {
        this.submit();
      }, scope: this
    }];
  },

  getListeners: function() {
    return {};
  },

    toggleFields: function (form_id, show, fields) {
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i]

            var source = Ext.getCmp(form_id + '-' + field)
            if (source === undefined) {
                console.error('Could not found field ' + field)
            } else {
                if (show) {
                    source.show()
                } else {
                    source.hide()
                }
                source.allowBlank = !show
            }
        }
    },
});
Ext.reg('bxsender-window-default', bxSender.window.Default);