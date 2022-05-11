<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Factory;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class StdoutLoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        return $container->get(LoggerFactory::class)->get('system');
    }
}
