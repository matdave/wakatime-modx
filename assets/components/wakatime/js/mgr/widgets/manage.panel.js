wakatime.panel.Manage = function (config) {
    config = config || {};
    Ext.apply(config, {
        border: false,
        baseCls: 'modx-formpanel',
        cls: 'container',
        items: [
            {
                html: '<h2>' + _('wakatime.manage.page_title') + '</h2>',
                border: false,
                cls: 'modx-page-header'
            },
            {
                xtype: 'modx-tabs',
                defaults: {
                    border: false,
                    autoHeight: true
                },
                border: true,
                activeItem: 0,
                hideMode: 'offsets',
                items: [
                    {
                        title: _('wakatime.manage.settings'),
                        layout: 'form',
                        items: [
                            {
                               html: _('wakatime.manage.settings_desc'),
                               cls: 'panel-desc'
                            },
                            {
                                layout: 'form',
                                defaults: {
                                    msgTarget: 'under',
                                    border: false,
                                    anchor: '100%',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    autoHeight: true,
                                },
                                labelAlign: 'top',
                                cls: 'main-wrapper',
                                items: [
                                    {
                                        fieldLabel: _('setting_wakatime.api_key') + ' <i style="font-weight: normal">(' + _('setting_wakatime.api_key_desc_shr') + ')</i>',
                                        name: 'wakatime.api_key',
                                        xtype: 'textfield',
                                        value: wakatime.config['wakatime.api_key'] || '',
                                        listeners: {
                                            change: function(field, value) {
                                                this.updateSetting(field, value);
                                            },
                                            scope: this
                                        }
                                    },
                                    {
                                       xtype: 'button',
                                       text: _('wakatime.manage.test'),
                                       cls: 'primary-button',
                                        handler: function() {
                                             MODx.Ajax.request({
                                                  url: wakatime.config.connectorUrl,
                                                  params: {
                                                    action:  'WakaTime\\Processors\\Settings\\Test'
                                                  },
                                                  listeners: {
                                                    success: {
                                                         fn: function (response) {
                                                              MODx.msg.alert(_('success'), response.message);
                                                         },
                                                         scope: this
                                                    },
                                                    failure: {
                                                         fn: function (response) {
                                                              MODx.msg.alert(_('error'), response.message);
                                                         },
                                                         scope: this
                                                    }
                                                  }
                                             });
                                        }
                                    },
                                    {
                                        fieldLabel: _('setting_wakatime.enabled'),
                                        name: 'wakatime.enabled',
                                        xtype: 'modx-combo-boolean',
                                        value: wakatime.config['wakatime.enabled'] || false,
                                        listeners: {
                                            change: function(field, value) {
                                                this.updateSetting(field, value);
                                            },
                                            scope: this
                                        }
                                    },
                                    {
                                        fieldLabel: _('setting_wakatime.project') + ' <i style="font-weight: normal">(' + _('setting_wakatime.project_desc_shr') + ')</i>',
                                        name: 'wakatime.project',
                                        xtype: 'textfield',
                                        value: wakatime.config['wakatime.project'] || '',
                                        listeners: {
                                            change: function(field, value) {
                                                this.updateSetting(field, value);
                                            },
                                            scope: this
                                        }
                                    },
                                    {
                                        fieldLabel: _('setting_wakatime.branch'),
                                        name: 'wakatime.branch',
                                        xtype: 'textfield',
                                        value: wakatime.config['wakatime.branch'] || '',
                                        listeners: {
                                            change: function(field, value) {
                                                this.updateSetting(field, value);
                                            },
                                            scope: this
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    });
    wakatime.panel.Manage.superclass.constructor.call(this, config);
};
Ext.extend(wakatime.panel.Manage, MODx.Panel, {
    updateSetting: function (field, value) {
        MODx.Ajax.request({
            url: wakatime.config.connectorUrl,
            params: {
                action:  'WakaTime\\Processors\\Settings\\Update',
                field: field.name,
                value: value
            },
            listeners: {
                success: {
                    fn: function (response) {
                        console.info(response.object);
                    },
                    scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    },
                    scope: this
                }
            }
        });
    }
});
Ext.reg('wakatime-panel-manage', wakatime.panel.Manage);
