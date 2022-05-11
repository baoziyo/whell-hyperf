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

    /* @phpstan-ignore-next-line */
    public function create(array $params);

    /* @phpstan-ignore-next-line */
    public function getByCache(int $id);

    /* @phpstan-ignore-next-line */
    public function findByCache(array $ids);
}
