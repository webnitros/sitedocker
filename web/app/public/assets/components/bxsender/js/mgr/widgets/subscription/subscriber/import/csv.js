bxSender.window.SubscriberImport = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('bxsender_subscriber_import_csv_btn')
        , id: 'bxsender-window-subscriber-import'
        , url: bxSender.config['connector_url']
        , action: 'mgr/subscription/subscriber/members/import/csv'
        , fileUpload: true
        , saveBtnText: _('import')
        , fields: [
            {
                xtype: 'bxsender-combo-segment',
                fieldLabel: _('bxsender_subscriber_segment'),
                description: _('bxsender_subscriber_segment_desc'),
                name: 'segments[]',
                id: config.id + '-segments',
                anchor: '99%',
                allowBlank: false
            },
            {
                html: _('bxsender_subscriber_import_csv_msg')
                , id: 'bxsende-import-impp-desc'
                , style: 'margin-bottom: 10px;margin-top: 15px;'
                , xtype: 'bxsender-description'
            },
            {
                xtype: 'fileuploadfield'
                , fieldLabel: _('bxsender_subscriber_import_csv_change')
                , buttonText: _('bxsender_subscriber_import_csv_btn_upload')
                , name: 'file'
                , id: 'bxsende-import-impp-file'
                , anchor: '100%'
                // ,inputType: 'file'
            },{
                xtype: 'textfield',
                fieldLabel: _('bxsender_subscriber_import_csv_fields'),
                description: _('bxsender_subscriber_import_csv_fields_desc'),
                name: 'fields',
                id: config.id + '-fields',
                anchor: '99%',
                allowBlank: false
            },{
                xtype: 'numberfield',
                fieldLabel: _('bxsender_subscriber_import_csv_offset'),
                name: 'offset',
                id: config.id + '-offset',
                anchor: '99%',
                allowBlank: false
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_fullname'),
                name: 'replace_fullname',
                id: config.id + '-replace_fullname',
                anchor: '99%',
                allowBlank: true
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_user_id'),
                description: _('bxsender_subscriber_replace_user_id_desc'),
                name: 'replace_user_id',
                id: config.id + '-replace_user_id',
                anchor: '99%',
                allowBlank: true
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_search_user'),
                description: _('bxsender_subscriber_search_user_desc'),
                name: 'search_user',
                id: config.id + '-search_user',
                anchor: '99%',
                allowBlank: true
            }
        ]
    })
    bxSender.window.SubscriberImport.superclass.constructor.call(this, config)

    // Trigger "fileselected" event
    var fp = Ext.getCmp('bxsende-import-impp-file')

    var onFileUploadFieldFileSelected = function (fp, fakeFilePath) {
        var fileApi = fp.fileInput.dom.files
        fp.el.dom.value = (typeof fileApi != 'undefined') ? fileApi[0].name : fakeFilePath.replace('C:\\fakepath\\', '')
    }
    fp.on('fileselected', onFileUploadFieldFileSelected)

}
Ext.extend(bxSender.window.SubscriberImport, MODx.Window)
Ext.reg('bxsender-window-subscriber-import', bxSender.window.SubscriberImport)

/*

bxSender.window.CreateMemberAddCsv = function (config) {
    config = config || {}
    this.ident = config.ident || 'subscriber-csv' + Ext.id()
    Ext.applyIf(config, {
        title: _('bxsender_subscriber_download_from_csv_file_title'),
        width: 600,
        baseParams: {
            action: 'mgr/subscription/subscriber/members/add',
        }
        , labelAlign: 'top'
        , enctype: 'multipart/form-data'
        , cls: 'container form-with-labels'
    })
    bxSender.window.CreateMemberAddCsv.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.window.CreateMemberAddCsv, bxSender.window.Default, {

    getFields: function (config) {

        return [
            {
                xtype: 'bxsender-combo-segment',
                fieldLabel: _('bxsender_subscriber_segment'),
                description: _('bxsender_subscriber_segment_desc'),
                name: 'segments[]',
                id: config.id + '-segments',
                anchor: '99%',
                allowBlank: false
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_fullname'),
                name: 'replace_fullname',
                id: config.id + '-replace_fullname',
                anchor: '99%',
                allowBlank: false
            },
            {
                xtype: 'xcheckbox',
                hideLabel: true,
                boxLabel: _('bxsender_subscriber_replace_user_id'),
                name: 'replace_user_id',
                id: config.id + '-replace_user_id',
                anchor: '99%',
                allowBlank: false
            }
        ]
    },

})
Ext.reg('bxsender-window-subscriber-member-add-csv', bxSender.window.CreateMemberAddCsv)*/
