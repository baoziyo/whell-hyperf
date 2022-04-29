<?php
/*
 * Sunny 2022/4/20 下午4:44
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Service\QueueService;

class RabbitMq extends Config
{
    protected $queueType = QueueService::QUEUE_TYPE_RABBITMQ;

    public function beforeSendValidateQueue(): bool
    {
        return env('QUEUE_AMQP', false);
    }

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool
    {
        return $this->biz->getAmqp()->produce(new $templateType($params));
    }
}
