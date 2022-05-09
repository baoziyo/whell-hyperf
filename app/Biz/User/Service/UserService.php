<?php
/*
 * Sunny 2021/11/26 上午11:42
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Service;

use App\Biz\User\Dao\UserDaoImpl;
use App\Biz\User\Type\UserSource\Original;
use App\Biz\User\Type\UserSource\WeChatApp;
use App\Core\Biz\Service\BaseService;

interface UserService extends BaseService
{
    public const USER_SOURCE_WECHAT_APP = 'weChatApp';

    public const USER_SOURCE_ORIGINAL = 'original';

    public const USER_SOURCE_STRATEGY_TYPE = [
        self::USER_SOURCE_WECHAT_APP => WeChatApp::class,
        self::USER_SOURCE_ORIGINAL => Original::class,
    ];

    public function register(array $data): UserDaoImpl;
}
