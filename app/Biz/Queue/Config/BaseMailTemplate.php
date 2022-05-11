<?php
/*
 * Sunny 2022/4/29 下午3:16
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Config;

use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\Inject;

abstract class BaseMailTemplate
{
    /**
     * @Inject
     */
    protected Biz $biz;

    /**
     * @return array[id,failUserIds,failDetails,...]
     */
    abstract public function handle(array $params): array;
}
