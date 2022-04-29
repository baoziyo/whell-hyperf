<?php
/*
 * Sunny 2021/11/24 下午5:38
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);
/*
 * Sunny 2021/3/10 下午2:22
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

namespace App\Exception\Handler;

use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

class ErrorExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    public function handle(\Throwable $throwable, ResponseInterface $response)
    {
        $this->biz->getService('Log:Log')->requestLog($this->request, $response, [
            'file' => $throwable->getFile(),
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ]);

        if (env('APP_ENV') === 'dev') {
            return $response;
        }

        $data = Json::encode([
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'data' => null,
            'time' => time(),
        ]);

        $this->stopPropagation();

        $status = substr((string) $throwable->getCode(), 0, 3);
        $status = $status <= 0 ? 500 : $status;

        return $response->withStatus($status)->withAddedHeader('Content-Type', 'application/json')->withBody(new SwooleStream($data));
    }

    public function isValid(\Throwable $throwable): bool
    {
        return true;
    }
}
