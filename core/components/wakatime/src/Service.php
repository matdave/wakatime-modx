<?php
namespace WakaTime;

use MODX\Revolution\modUser;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\modX;
use MatDave\MODXPackage\Service as BaseService;
use WakaTime\Services\Wakatime;
use xPDO\xPDO;

class Service extends BaseService
{
    public const VERSION = '0.0.1';
    public $namespace = 'wakatime';

    public CONST CACHE_OPTIONS = [
        xPDO::OPT_CACHE_KEY => 'heartbeat',
    ];

    public function createHeartbeat($entity, $is_write = false, $language = 'html', $category = 'building'): void
    {
        /**
        * @var modUser $user
        */
        $user = $this->modx->user;
        if (!$user || !$user->get('id')) {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Wakatime user not found');
            return;
        }
        $entity = $entity ?? $this->modx->getOption('site_url');
        $cache = $this->getCache($user);
        if ($cache && $cache == $entity) {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Wakatime heartbeat already sent');
            return;
        }
        $settings = $user->getMany('UserSettings', ['namespace' => $this->namespace]);
        $userSettings = [];
        foreach ($settings as $setting) {
            $userSettings[$setting->get('key')] = $setting->get('value');
        }
        $enabled = $this->getOption('wakatime.enabled', $userSettings, false);
        if (!$enabled) {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Wakatime disabled '. json_encode($userSettings));
            return;
        }
        $project = $this->getOption('wakatime.project', $userSettings, $this->modx->getOption('site_name'));
        $branch = $this->getOption('wakatime.branch', $userSettings, 'main');
        $heartbeat = new Services\Wakatime\Heartbeat([
            'entity' => $entity,
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
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Wakatime heartbeat sent: ' . json_encode($hb));
            $this->setCache($user, $entity);
        } catch (\Exception $e) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Wakatime error: ' . $e->getMessage());
        }
    }

    public function test()
    {
        try {
            $wt = new Wakatime($this);
            return $wt->test();
        } catch (\Exception $e) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Wakatime error: ' . $e->getMessage());
            return false;
        }
    }

    public function setCache(modUser $user, $entity): void
    {
        /**
         * @var modX $modx
         */
        $modx = $this->modx;
        $modx->cacheManager->set('heartbeat-' . $user->id, $entity, 120, $this::CACHE_OPTIONS);
    }

    public function getCache(modUser $user)
    {
        /**
         * @var modX $modx
         */
        $modx = $this->modx;
        return $modx->cacheManager->get('heartbeat-' . $user->id, $this::CACHE_OPTIONS);
    }
}
