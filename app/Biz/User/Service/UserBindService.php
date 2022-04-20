<?php
/*
 * Sunny 2021/11/26 上午11:42
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Service;

use App\Core\Biz\Service\BaseService;

interface UserBindService extends BaseService
{
    public const WECHAT_TYPE = 'weChatApp';

    public function createOrUpdate(array $params, string $fromKey): bool;

    public function getFromIdByUserIdAndType(int $userId, string $type): string;
}