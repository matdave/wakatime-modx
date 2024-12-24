<?php

namespace WakaTime\Processors\Settings;

use MODX\Revolution\modUserSetting;
use MODX\Revolution\Processors\Processor;

class Test extends Processor
{
    public function process()
    {
        $service = new \WakaTime\Service($this->modx);
        $response = $service->test();
        if ($response === false || !isset($response['data'])) {
            return $this->failure($this->modx->lexicon('wakatime.err_settings.test_failed'));
        }
        return $this->success($this->modx->lexicon('wakatime.success_settings.test_passed', $response['data']), $response);
    }
}