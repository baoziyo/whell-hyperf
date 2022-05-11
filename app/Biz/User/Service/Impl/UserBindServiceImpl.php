<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Service\Impl;

use App\Biz\User\Dao\UserBindDaoImpl;
use App\Biz\User\Service\UserBindService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class UserBindServiceImpl extends BaseServiceImpl implements UserBindService
{
    protected $dao = UserBindDaoImpl::class;

    public function createOrUpdate(array $params, string $fromKey): bool
    {
        $userBindExists = UserBindDaoImpl::query()->where('fromId', $params['fromId'])->exists();
        if ($userBindExists) {
            UserBindDaoImpl::query()->where('fromId', $params['fromId'])->update(['fromKey' => $fromKey]);
            return true;
        }

        $this->create($params);
        return true;
    }

    public function getFromIdByUserIdAndType(int $userId, string $type): string
    {
        return UserBindDaoImpl::query()->where([
            'userId' => $userId,
            'type' => $type,
        ])->value('fromId');
    }
}
