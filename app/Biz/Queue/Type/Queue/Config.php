<?php
/*
 * Sunny 2022/4/21 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Config\QueueStrategy;
use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\Inject;

abstract class Config implements QueueStrategy
{
    /**
     * @Inject
     */
    protected Biz $biz;

    protected string $queueType = '';

    abstract public function beforeSendValidateQueue(): bool;

    abstract public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool;
}
