<?php
/*
 * Sunny 2022/4/20 下午4:44
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Service\QueueService;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Di\Annotation\Inject;

class Redis extends Config
{
    /**
     * @Inject
     * @var DriverInterface
     */
    protected $driver;

    protected $queueType = QueueService::QUEUE_TYPE_REDIS;

    public function beforeSendValidateQueue(): bool
    {
        return env('QUEUE_REDIS', false);
    }

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool
    {
        return $this->driver->push(new $templateType($params), $delay);
    }
}
