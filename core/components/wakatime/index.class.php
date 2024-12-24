<?php

use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUserSetting;

abstract class WakaTimeBaseManagerController extends modExtraManagerController {
    /** @var \WakaTime\Service $wakatime */
    public $wakatime;

    public function initialize(): void
    {
        $this->wakatime = $this->modx->services->get('wakatime');

        $this->addCss($this->wakatime->getOption('cssUrl') . 'mgr.css');
        $this->addJavascript($this->wakatime->getOption('jsUrl') . 'mgr/wakatime.js');
        $sysSettings = $this->modx->getCollection(modSystemSetting::class, ['namespace' => 'wakatime']);
        foreach ($sysSettings as $setting) {
            $this->wakatime->config[$setting->get('key')] = $setting->get('value');
        }
        $userSettings = $this->modx->getCollection(modUserSetting::class, ['user' => $this->modx->user->get('id'), 'namespace' => 'wakatime']);
        foreach ($userSettings as $setting) {
            $this->wakatime->config[$setting->get('key')] = $setting->get('value');
        }
        $this->addHtml('
            <script type="text/javascript">
                Ext.onReady(function() {
                    wakatime.config = '.$this->modx->toJSON($this->wakatime->config).';
                });
            </script>
        ');

        parent::initialize();
    }

    public function getLanguageTopics(): array
    {
        return array('wakatime:default');
    }

    public function checkPermissions(): bool
    {
        return true;
    }
}
