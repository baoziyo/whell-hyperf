<?php
/*
 * Sunny 2022/4/20 下午2:12
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Wechat\Client;

use App\Biz\Wechat\Exception\WechatException;
use App\Biz\Wechat\Service\WechatService;
use App\Core\Biz\Container\Biz;
use GuzzleHttp\Client;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Codec\Json;

class WechatClient
{
    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    public function codeToSession(string $code, string $appId = null, string $appSecret = null): string
    {
        $response = $this->getClient()->get('/sns/jscode2session', [
            'query' => [
                'appid' => $appId ?? $this->getWechatService()->getAppId(),
                'secret' => $appSecret ?? $this->getWechatService()->getAppSecret(),
                'js_code' => $code,
                'grant_type' => 'authorization_code',
            ],
        ]);

        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, null, null, [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    public function getAccessToken(string $appId = null, string $appSecret = null): array
    {
        $response = $this->getClient()->get('/cgi-bin/token', [
            'query' => [
                'grant_type' => 'client_credential',
                'appid' => $appId ?? $this->getWechatService()->getAppId(),
                'secret' => $appSecret ?? $this->getWechatService()->getAppSecret(),
            ],
        ]);

        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, null, null, [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    public function sendMessage(string $userOpenId, array $data, string $appId = null)
    {
        $data = array_merge($data, ['appid' => $appId ?? $this->getWechatService()->getAppId()]);
        $response = $this->getClient()->post('/cgi-bin/message/wxopen/template/uniform_send', [
            'query' => [
                'access_token' => $this->getWechatService()->getAccessToken(),
            ],
            'body' => [
                'touser' => $userOpenId,
                'mp_template_msg' => Json::encode($data),
            ],
        ]);

        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, null, null, [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    private function getClient(): Client
    {
        return $this->biz->getClient([
            'base_uri' => 'https://api.weixin.qq.com',
        ]);
    }

    private function getWechatService(): WechatService
    {
        return $this->biz->getService('Wechat:Wechat');
    }
}
