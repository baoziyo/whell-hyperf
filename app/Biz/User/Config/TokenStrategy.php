<?php
/*
 * Sunny 2021/11/24 下午8:10
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Config;

use Hyperf\HttpServer\Contract\RequestInterface;

interface TokenStrategy
{
    public const EXPIRES_TIME = 36000;

    public const REFRESH_EXPIRES_TIME = 72000;

    public function generateToken(array $params = []): array;

    public function refreshToken($refreshToken): array;

    public function validate(RequestInterface $request): array;
}
