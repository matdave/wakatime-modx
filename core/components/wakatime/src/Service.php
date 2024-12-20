<?php
namespace WakaTime;

use MODX\Revolution\modUser;
use MODX\Revolution\modX;
use MatDave\MODXPackage\Service as BaseService;
use WakaTime\Services\Wakatime;

class Service extends BaseService
{
    public const VERSION = '0.0.1';
    public $namespace = 'wakatime';

    public function createHeartbeat($entity, $is_write = false, $language = 'html', $category = 'building'): void
    {
        /**
        * @var modUser $user
        */
        $user = $this->modx->user;
        if (!$user || !$user->get('id')) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Wakatime user not found');
            return;
        }
        $settings = $user->getMany('UserSettings', ['namespace' => $this->namespace]);
        $userSettings = [];
        foreach ($settings as $setting) {
            $userSettings[$setting->get('key')] = $setting->get('value');
        }
        $enabled = $this->getOption('wakatime.enabled', $userSettings, false);
        if (!$enabled) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Wakatime disabled '. json_encode($userSettings));
            return;
        }
        $project = $this->getOption('wakatime.project', $userSettings, $this->modx->getOption('site_name'));
        $branch = $this->getOption('wakatime.branch', $userSettings, 'main');

        $heartbeat = new Services\Wakatime\Heartbeat([
            'entity' => $entity ?? $this->modx->getOption('site_url'),
            'type' => 'app',
            'category' => $category,
            'time' => time(),
            'project' => $project,
            'branch' => $branch,
            'is_write' => $is_write,
            'language' => $language,
        ]);
        try {
            $wt = new Wakatime($this);
            $hb = $wt->sendHeartbeat($heartbeat);
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Wakatime heartbeat sent: ' . json_encode($hb));
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Wakatime error: ' . $e->getMessage());
        }
    }
}
