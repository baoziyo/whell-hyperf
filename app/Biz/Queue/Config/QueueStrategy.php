<?php
/*
 * Sunny 2022/4/20 下午4:43
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Config;

interface QueueStrategy
{
    public function beforeSendValidateQueue(): bool;

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool;
}
