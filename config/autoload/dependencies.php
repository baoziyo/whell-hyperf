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
return [
    Hyperf\HttpServer\CoreMiddleware::class => App\Middleware\CoreMiddleware::class,
    \App\Core\Biz\Container\Biz::class => \App\Core\Biz\Container\BizImpl::class,
    Hyperf\Contract\StdoutLoggerInterface::class => \App\Biz\Log\Factory\StdoutLoggerFactory::class,
    Hyperf\Validation\Contract\ValidatorFactoryInterface::class => \App\Core\Validation\Factory\ValidatorFactory::class,
];
