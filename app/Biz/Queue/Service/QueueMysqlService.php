<?php
/*
 * Sunny 2022/4/20 下午4:39
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Service;

use App\Core\Biz\Service\BaseService;

interface QueueMysqlService extends BaseService
{
    public function producer(int $id, int $delay = 0): bool;

    public function consumer(): bool;
}
