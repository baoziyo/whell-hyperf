<?php
/*
 * Sunny 2022/4/28 下午6:18
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Crontab;

use App\Core\Crontab\BaseCrontab;
use Hyperf\Crontab\Annotation\Crontab;

/**
 * @Crontab(name="deleteOldFileLogs", rule="50 23 * * *", callback="execute", memo="清理旧日志")
 */
class DeleteOldFileLogsCrontab extends BaseCrontab
{
    protected function execute(): bool
    {
        return true;
    }
}
