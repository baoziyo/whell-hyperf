<?php
/*
 * Sunny 2022/4/19 上午10:44
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Token\Service;

use App\Biz\Token\Dao\TokenDaoImpl;
use App\Core\Biz\Service\BaseService;

interface TokenService extends BaseService
{
    public const WECHAT_ACCESS_TOKEN = 'wechat_access_token';

    public function getToken(string $key): string;

    public function updateToken(string $key, string $value, int $expires): bool;

    public function createToken(string $key, string $value, int $expires): TokenDaoImpl;
}
