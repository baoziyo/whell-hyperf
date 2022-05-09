<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Amqp;

use App\Core\Biz\Container\Biz;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Di\Annotation\Inject;

abstract class BaseConsumer extends ConsumerMessage
{
    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    public function isEnable(): bool
    {
        return (bool) env('QUEUE_AMQP', false);
    }
}
