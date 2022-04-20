<?php
/*
 * Sunny 2021/11/24 下午8:10
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Config;

interface  UserSourceStrategy
{
    /**
     * @param array $params
     * @return array
     */
    public function buildRegisterParams(array $params): array;
}
