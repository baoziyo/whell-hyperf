<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Amqp\Producer;

use App\Core\Amqp\BaseProducer;
use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Utils\Codec\Json;

/**
 * @Producer(exchange="createGraylog", routingKey="createGraylog")
 */
class CreateGraylogProducer extends BaseProducer
{
    public function __construct($level, $message, $context)
    {
        $this->payload = Json::encode([
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ]);
    }
}
