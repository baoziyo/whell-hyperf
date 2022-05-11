<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Core\Biz\Container;

use GuzzleHttp\Client;
use Hyperf\Amqp\Producer;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;

interface Biz
{
    public function getVersion(string $appointVersion = ''): string;

    /* @phpstan-ignore-next-line */
    public function getService(string $serviceName, string $version = '');

    public function getRedis(string $poolName = 'default'): Redis;

    public function getClient(array $config = [], bool $grayLog = true): Client;

    public function getAmqp(): Producer;

    public function getContainer(): ContainerInterface;
}
