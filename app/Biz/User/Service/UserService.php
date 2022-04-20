<?php
/*
 * Sunny 2021/11/26 上午11:42
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Service;

use App\Biz\User\Dao\UserDaoImpl;
use App\Biz\User\Type\UserSource\WeChatApp;
use App\Core\Biz\Service\BaseService;

interface UserService extends BaseService
{
    public const USER_SOURCE_STRATEGY_TYPE = [
        'weChatApp' => WeChatApp::class,
    ];

    public function register(array $data): UserDaoImpl;
}