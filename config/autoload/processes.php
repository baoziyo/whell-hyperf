<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
$extends = [];

if (env('QUEUE_REDIS', false) === true) {
    $extends[] = Hyperf\AsyncQueue\Process\ConsumerProcess::class;
}

return array_merge([
    \Hyperf\Crontab\Process\CrontabDispatcherProcess::class,
], $extends);
