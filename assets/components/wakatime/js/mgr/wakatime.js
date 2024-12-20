var WakaTime = function (config) {
    config = config || {};
    WakaTime.superclass.constructor.call(this, config);
};
Ext.extend(WakaTime, Ext.Component, {

    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    field: {},
    config: {},

});
Ext.reg('wakatime', WakaTime);
wakatime = new WakaTime();
