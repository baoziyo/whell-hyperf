<?php
/*
 * Sunny 2022/4/18 下午5:08
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Wechat\Service;

use App\Core\Biz\Service\BaseService;

interface WechatService extends BaseService
{
    public function codeToSession(string $code): string;

    public function sendMessage(int $userId, array $data): void;

    public function getAccessToken(): string;

    public function getAppId(): string;

    public function getAppSecret(): string;
}
