<?php
/*
 * Sunny 2022/4/18 下午5:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */


declare(strict_types=1);

namespace App\Controller\Api\Login;

use App\Biz\Wechat\Service\WechatService;
use App\Controller\AbstractController;

class WechatLoginController extends AbstractController
{
    public function post()
    {
        $code = $this->request->post('code', '');

        $response = $this->getWechatService()->codeToSession($code);

        return $this->buildRequest($response);
    }

    private function getWechatService(): WechatService
    {
        return $this->biz->getService('Wechat:Wechat');
    }
}