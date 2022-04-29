<?php
/*
 * Sunny 2021/11/24 下午8:11
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Type\UserSource;

use App\Biz\User\Config\UserSourceStrategy;
use Hyperf\Utils\Str;

class WeChatApp implements UserSourceStrategy
{
    public function buildRegisterParams(array $params): array
    {
        $params['password'] = Str::random();

        return $params;
    }
}
