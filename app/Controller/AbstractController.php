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

namespace App\Controller;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

abstract class AbstractController
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected ResponseInterface $response;

    protected Biz $biz;

    public function __construct(ContainerInterface $container, RequestInterface $request, ResponseInterface $response, Biz $biz)
    {
        $this->biz = $biz;
        $this->response = $response;
        $this->request = $request;
        $this->container = $container;
    }

    /* @phpstan-ignore-next-line */
    protected function buildRequest($data = [], string $type = 'json', int $code = 200, string $message = '操作成功.'): PsrResponseInterface
    {
        return $this->response->{$type}([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'time' => time(),
        ]);
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
