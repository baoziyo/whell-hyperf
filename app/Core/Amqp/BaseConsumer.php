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
use PhpAmqpLib\Message\AMQPMessage;

abstract class BaseConsumer extends ConsumerMessage
{
    /**
     * @Inject()
     * @var Biz
     */
    protected $biz;

    abstract public function handle($data, AMQPMessage $message): string;

    public function consumeMessage($data, AMQPMessage $message): string
    {
        return $this->handle($data, $message);
    }

    public function isEnable(): bool
    {
        return (bool)env('RABBIT_ENABLE', true);
    }
}
