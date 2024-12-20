<?php

namespace WakaTime\Services;

use Exception;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserSetting;
use Wakatime\Service;
use MatDave\MODXPackage\Traits\Curl;
use MODX\Revolution\modX;
use WakaTime\Services\Wakatime\Heartbeat;

class Wakatime
{
    use Curl;

    private Service $service;
    private modX $modx;

    private modUser $user;

    private string $token;

    private string $api = 'https://wakatime.com/api/v1';

    /**
     * @throws Exception
     */
    public function __construct($service)
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->user = $this->modx->user;

        $token = $this->modx->getObject(modUserSetting::class, ['key' => 'wakatime.api_key', 'user' => $this->user->get('id')]);

        if ($token) {
            $this->token = $token->get('value');
        }

        if (empty($this->token)) {
            throw new Exception('Wakatime api key is empty');
        }
    }

    public function sendHeartbeat(Heartbeat $data): array
    {
        $url = '/users/current/heartbeats?api_key=' . $this->token;

        return $this->sendRequest($url, 'POST', $data->toArray());
    }

    private function sendRequest($path, $method = 'GET', $params = []): array
    {
        // editor / os user agent
        $serviceVersion = $this->service::VERSION;
        $version = $this->modx->getVersionData();
        $editor = "MODX; Revolution; rv:" . $version['full_version'];
        $USER_AGENT = "WakaTime/$serviceVersion ($editor)";
        $response = $this->curl(
            $path,
            $method,
            $params,
            [
                'Accept: application/json',
                'X-Machine-Name: ' . $this->modx->getOption('site_url'),
            ],
            [
                CURLOPT_USERAGENT => $USER_AGENT,
            ]
        );
        return json_decode($response, true);
    }
}
