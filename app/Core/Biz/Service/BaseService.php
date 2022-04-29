<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Service;

interface BaseService
{
    public const ENABLED = 'enabled';

    public const DISABLED = 'disabled';

    public const DOING = 'doing';

    public const FINISHED = 'finished';

    public const FAILED = 'failed';

    public function create(array $params);

    public function getByCache(int $id);

    public function findByCache(array $ids);
}
