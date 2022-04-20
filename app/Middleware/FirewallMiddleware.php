<?php
/*
 * Sunny 2021/11/24 下午5:38
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Middleware;

use App\Biz\User\Service\TokenService;
use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;

class FirewallMiddleware implements MiddlewareInterface
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
     * @var HttpResponse
     */
    protected $response;

    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    // eg: '/^\/product$\??(.*)/',
    protected $whitelist = [
        'POST' => [
            '/^\/login$\??(.*)/',
            '/^\/login\/wechat$\??(.*)/',
        ],
        'GET' => [
            '/^\/captcha$\??(.*)/',
        ],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        if (env('APP_ENV') === 'dev') {
            $this->whitelist['POST'] = array_merge($this->whitelist['POST'], ['/^\/test\??(.*)/']);
            $this->whitelist['GET'] = array_merge($this->whitelist['GET'], ['/^\/test\??(.*)/']);
            $this->whitelist['PATCH'] = array_merge($this->whitelist['PATCH'], ['/^\/test\??(.*)/']);
            $this->whitelist['DELETE'] = array_merge($this->whitelist['DELETE'], ['/^\/test\??(.*)/']);
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->checkWhitelists($request->url())) {
            return $handler->handle($request);
        }

        $type = $this->request->getHeaderLine('Token-Type');
        $info = $this->getTokenService()->validate($type, $this->request);
        Context::set('user', Json::encode($info));

        return $handler->handle($request);
    }

    protected function checkWhitelists($url): bool
    {
        foreach ($this->whitelist as $method => $whitelist) {
            foreach ($whitelist as $uri) {
                if ($this->request->getMethod() === $method && !empty(parse_url($url)['path']) && preg_match($uri, parse_url($url)['path'])) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getTokenService(): TokenService
    {
        return $this->biz->getService('User:Token');
    }
}
