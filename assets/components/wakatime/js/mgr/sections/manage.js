wakatime.page.Manage = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [
            {
                xtype: 'wakatime-panel-manage',
                renderTo: 'wakatime-panel-manage-div'
            }
        ]
    });
    wakatime.page.Manage.superclass.constructor.call(this, config);
};
Ext.extend(wakatime.page.Manage, MODx.Component);
Ext.reg('wakatime-page-manage', wakatime.page.Manage);
