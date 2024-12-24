<?php

namespace WakaTime\Processors\Settings;

use MODX\Revolution\modUserSetting;
use MODX\Revolution\Processors\Processor;

class Update extends Processor
{

    public function process()
    {
        $field = $this->getProperty('field');
        $value = $this->getProperty('value');
        if (empty($field)) {
            return $this->failure($this->modx->lexicon('wakatime.err_settings.field_required'));
        }
        $user = $this->modx->user;
        $setting = $this->modx->getObject(modUserSetting::class, ['key' => $field, 'user' => $user->get('id')]);
        $isnew = false;
        if (!$setting) {
            $setting = $this->modx->newObject(modUserSetting::class);
            $setting->set('key', $field);
            $setting->set('user', $user->get('id'));
            $setting->set('namespace', 'wakatime');
            $setting->set('xtype', 'textfield');
            $isnew = true;
        }
        if ($field === 'wakatime.enabled') {
            $value = $value === 'true' ? '1' : '0';
            $setting->set('xtype', 'combo-boolean');
        }
        $setting->set('value', $value);
        if ($value === '') {
            if ($isnew) {
                return $this->failure($this->modx->lexicon('wakatime.err_settings.field_required'));
            }
            $setting->remove();
            $this->modx->cacheManager->refresh();
            return $this->success('', $setting);
        }
        $setting->save();
        $this->modx->cacheManager->refresh();
        return $this->success('', $setting);
    }

}