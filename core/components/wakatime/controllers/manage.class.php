<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';

class WakaTimeManageManagerController extends WakaTimeBaseManagerController
{

    public function process(array $scriptProperties = []): void
    {
    }

    public function getPageTitle(): string
    {
        return $this->modx->lexicon('wakatime');
    }

    public function loadCustomCssJs(): void
    {
        $this->addLastJavascript($this->wakatime->getOption('jsUrl') . 'mgr/widgets/manage.panel.js');
        $this->addLastJavascript($this->wakatime->getOption('jsUrl') . 'mgr/sections/manage.js');

        $this->addHtml(
            '
            <script type="text/javascript">
                Ext.onReady(function() {
                    MODx.load({ xtype: "wakatime-page-manage"});
                });
            </script>
        '
        );
    }

    public function getTemplateFile(): string
    {
        return $this->wakatime->getOption('templatesPath') . 'manage.tpl';
    }

}
