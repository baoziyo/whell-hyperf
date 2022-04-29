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

abstract class AbstractController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Biz
     */
    protected $biz;

    public function __construct(ContainerInterface $container, RequestInterface $request, ResponseInterface $response, Biz $biz)
    {
        $this->biz = $biz;
        $this->response = $response;
        $this->request = $request;
        $this->container = $container;
    }

    protected function buildRequest($data = [], $type = 'json', $code = 200, $message = '操作成功.')
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
