if (typeof Ext.ux.Ace !== "undefined") {
    Ext.override(Ext.ux.Ace,{
        onDestroy : function(){
            Ext.ux.Ace.superclass.onDestroy.call(this);
        }
    });
}

modDevTools.panel.Elements = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false,
        baseCls: 'modx-formpanel',
        layout: 'auto',
        width: '100%',
        editors: this.editors,
        parentPanel: config.ownerCt.ownerCt.ownerCt, //modx-panel-(chunk/template/...)
        items: [{
            html: this.getIntro(),
            border: false,
            cls: 'modx-page-header container'
        },{
            layout: 'accordion',
            border:false
            ,layoutConfig:{animate:true}
            ,defaults: {
                bodyStyle: 'padding:15px'
                ,renderHidden : true
                ,stateEvents: ["collapse","expand"]
                ,getState:function() {
                    return {collapsed:this.collapsed};
                }
                ,border:false
            }
            ,items:[]
        }]
    });

    this.dropTargets = [];
    this.getItems();

    var tabs = config.ownerCt.ownerCt;
    if (!tabs.isDevToolsEventSet) {
        tabs.addListener('tabchange', function(){
            this.disableSaveButton(false);
        }, this);
        tabs.isDevToolsEventSet = true;
    }

    config.parentPanel.on('success', function(){
        this.getItems();
    }, this);

    this.config = config;
    modDevTools.panel.Elements.superclass.constructor.call(this,config);
};

Ext.extend(modDevTools.panel.Elements, MODx.Panel, {
    getItems: function() {
        var params = this.config.config;
        var baseParams = this.config.params;
        var store = new Ext.data.JsonStore({
            url: modDevTools.config.connector_url,
            baseParams: baseParams,
            autoLoad: true,
            fields: ['id', 'name', 'snippet', 'virtual', 'category'],
            root: 'results',
            totalProperty: 'total',
            autoDestroy: true,
            listeners: {
                'load': {fn:function(opt,records,c){
                    this.items.itemAt(1).removeAll();
                    for (var i = 0; i < records.length; i++) {
                        var r = records[i].data;
                        var item = {
                            stateId: 'state' + r.name,
                            id: 'tools-' + params.element + '-' + i,
                            title: r.name + ' (' + r.id + ')',
                            headerCfg: {
                                cls: 'x-panel-header',
                                style: {
                                    background: r.virtual ? '#f0ad4e' : '#ececec',
                                    padding: '10px',
                                    margin: '0 0 10px 0'
                                }
                            },
                            keys: [{
                                key: "s",
                                ctrl:true,
                                scope: this,
                                fn: function(){
                                    this.focusedEditor.ownerCt.find('xtype', 'button')[0].fireEvent('click');
                                }
                            }],
                            autoHeight: true,
                            tbar: [{
                                xtype: 'button',
                                id: 'save-' + params.element + '-' + r.name,
                                text: _('save'),
                                cls: 'primary-button',
                                disabled: true,
                                keys: [{
                                    key: MODx.config.keymap_save || 's'
                                    ,ctrl: true
                                }],
                                listeners: {
                                    click: this.saveElement
                                }
                            },'-', {xtype: 'label', text:_('category')+':' },{
                                xtype: 'modx-combo-category'
                                ,name: 'category'
                                ,id: params.element+'-category-'+ r.name
                                ,anchor: '100%'
                                ,value: r.category || 0
                                ,listeners: {
                                    select: {fn:function(){
                                        var btn = this.ownerCt.get(0);
                                        if (btn.disabled) {
                                            btn.setDisabled(false);
                                        }
                                    }}
                                }
                            },'-',(!r.virtual ? {
                                xtype: 'button',
                                id: 'open-' + params.element + '-' + r.name,
                                text: _('open'),
                                element: params.element,
                                elementId: r.id,
                                listeners: {
                                    click: {fn:function() {
                                        var action = 'element/'+this.element+'/update';
                                        window.open('?a=' + (modDevTools.modx23 ? action : MODx.action[action]) + '&id=' + this.elementId);
                                    }}
                                }
                            }: '')],
                            items: [{
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea',
                                mimeType: params.mimeType,
                                modxTags : params.modxTags,
                                value: this.getElementValue(r),
                                width: '100%',
                                height: 300,
                                id: params.element + '-editor-' + r.name,
                                record: r,
                                enableKeyEvents: true,
                                listeners: {
                                    keyup: {fn:function(){
                                        var button = Ext.getCmp('save-' + params.element + '-' + this.record.name);
                                        if (this.value !== this.getValue()) {
                                            if (button.disabled) {
                                                button.setDisabled(false);
                                            }
                                        } else {
                                            if (!button.disabled) {
                                                button.setDisabled(true);
                                            }
                                        }
                                    }},
                                    'focus': {fn:function(editor){
                                        this.focusedEditor = editor;
                                        this.disableSaveButton(true);
                                    }, scope: this},
                                    afterrender: {fn:function(field) {
                                        if (field.xtype == 'modx-texteditor') {
                                            var editor = field.editor;
                                            var name = this.parentPanel.record.name;
                                            editor.findAll(name);

                                            var ranges = this.highlightElements(editor, name);

                                            if (ranges.length > 0) {
                                                editor.gotoLine(ranges[0].end.row+1,ranges[0].end.column);
                                            }

                                            var _self = this;
                                            editor.getSession().on('change', function(){
                                                _self.highlightElements(editor, name);
                                            });
                                        }

                                        var dropTargets = this.dropTargets;
                                        var el = field.getEl();
                                        if (el) {
                                            var dropTarget = MODx.load({
                                                xtype: 'modx-treedrop',
                                                target: field,
                                                targetEl: el,
                                                onInsert: (function(s,b,a){
                                                    field.insertAtCursor(s);
                                                    return true;
                                                }).bind(field.editor),
                                                iframe: true
                                            });
                                            dropTargets.push(dropTarget);
                                        }
                                    }, scope: this}
                                }
                            },this.loadProperties(r)],
                            listeners: {
                                'beforecollapse':{fn:function(a,b){
                                    return b !== true; // prevent collapse if not collapse directly on panel
                                },scope: this},
                                'render': this.loadTip,
                                'expand':{fn:function(a){
                                    // fix collapsed combo zero width
                                    var combo = a.getTopToolbar().items.item(3)
                                    combo.setSize(combo.getWidth(),combo.getHeight());
                                }, scope: this}
                            },
                            collapsed:false,
                            collapsible: true,
                            virtual: r.virtual
                        };
                        this.items.itemAt(1).add(item);
                    }
                    this.doLayout();
                },scope:this}
            }
        });
    },

    saveElement: function () {
        var btn = this;
        if (btn.disabled) return false;
        btn.setText(_('saving'));
        var input = btn.ownerCt.ownerCt.items.item(0);
        var panel = btn.ownerCt.ownerCt.ownerCt.ownerCt;
        var params = panel.config.config;
        MODx.Ajax.request({
            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/' + params.element + '.php'),
            params: panel.getUpdateParams(input),
            listeners: {
                'success': {fn:function(r) {
                    if (r.success) {
                        input.value = input.getValue();
                        btn.setDisabled(true);
                        btn.setText(_('save'));
                        // при создании чанка его надо подгрузить
                        if (params.element == 'chunk' && input.record.id == 0) {
                            Ext.getCmp('tools-panel-chunks').getItems();
                        }
                    }
                },scope:this}
            }
        });
    },

    loadTip: function(){
        if (this.virtual) {
            Ext.QuickTips.register({
                target:  this.header
                ,text: _('moddevtools_virtual_chunk_desc')
                ,enabled: true
            });
        }
    },

    getUpdateParams: function(input) {
        var action = input.record.id ? 'update' : 'create';
        var category = Ext.getCmp( this.config.config.element+'-category-'+ input.record.name).getValue();
        return {
            action: modDevTools.modx23 ? 'element/' + this.config.config.element + '/' + action : action,
            id: input.record.id,
            name: input.record.name,
            snippet: input.getValue(),
            parent: this.config.params.parent,
            link_type: this.config.params.link_type,
            category: category
        };
    },

    disableSaveButton: function(value) {
        var btns = Ext.getCmp('modx-action-buttons');
        if (btns && btns.get(0)) {
            btns.get(0).setDisabled(value);
        }
    },

    highlightElements: function(editor, name) {
        var markers = editor.getSession().getMarkers(false);
        for (var id in markers) {
            if (markers[id].clazz.indexOf('ace_selected-word') === 0) {
                editor.getSession().removeMarker(id);
            }
        }

        editor.$search.set({needle:name});
        var ranges = editor.$search.findAll(editor.session);

        for (var i=0; i<ranges.length; i++) {
             editor.getSession().addMarker(ranges[i],"ace_selected-word", "text");
        }

        return ranges;
    }

    ,destroy: function() {
        for (var i = 0; i < this.dropTargets.length; i++) {
            this.dropTargets[i].destroy();
        }
        modDevTools.panel.Elements.superclass.destroy.call(this);
    }
});
