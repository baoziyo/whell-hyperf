<?php
/*
 * Sunny 2022/4/28 下午3:54
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Core\Crontab;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\Inject;

abstract class BaseCrontab
{
    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
