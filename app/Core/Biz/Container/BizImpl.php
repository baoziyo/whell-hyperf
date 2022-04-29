<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Core\Biz\Container;

use App\Biz\Guzzle\Formatter\MessageFormatter;
use App\Biz\Guzzle\Middleware\Middleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Amqp\Producer;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Guzzle\PoolHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class BizImpl implements Biz
{
    /**
     * @Inject
     * @var ConfigInterface
     */
    public $config;

    /**
     * @Inject
     * @var ContainerInterface
     */
    public $container;

    protected $serviceDir = 'App\Biz\%s%s\Service\Impl\%sServiceImpl';

    public function getVersion(string $appointVersion = ''): string
    {
        if (empty($appointVersion)) {
            $request = make(RequestInterface::class);
            if (! Context::get(ServerRequestInterface::class) || ! $request->hasHeader('version')) {
                return strtolower((string) env('SYSTEM_API_VERSION', ''));
            }
            return strtolower((string) $request->header('version'));
        }

        return strtolower($appointVersion);
    }

    public function getService(string $serviceName, string $version = '')
    {
        $version = $this->getVersion($version);

        [$serviceDir, $file] = explode(':', $serviceName);
        $version = $version !== '' ? '\\' . $version : '';
        $class = sprintf($this->serviceDir, $serviceDir, $version, $file);

        if (class_exists($class)) {
            return make($class);
        }

        return make(sprintf($this->serviceDir, $serviceDir, '', $file));
    }

    public function getRedis(string $poolName = 'default'): Redis
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get($poolName);
    }

    public function getClient(array $config = [], bool $grayLog = true): Client
    {
        $handler = null;
        if (Coroutine::inCoroutine()) {
            $handler = make(PoolHandler::class, [
                'option' => [
                    'max_connections' => (int) env('GUZZLE_MAX_CONNECTIONS', 50),
                ],
            ]);
        }

        $handlerStack = HandlerStack::create($handler);
        if ($grayLog) {
            $log = Middleware::log($this->container->get(LoggerFactory::class)->get('guzzle'), new MessageFormatter(MessageFormatter::CLF));
        } else {
            $log = \GuzzleHttp\Middleware::log($this->container->get(LoggerFactory::class)->get('guzzle'), new MessageFormatter(MessageFormatter::CLF));
        }
        $handlerStack->push($log);

        return make(ClientFactory::class)->create(array_merge([
            'handler' => $handlerStack,
            'http_errors' => false,
        ], $config));
    }

    public function getCurrentUser(): array
    {
        if (Context::get('user') === null) {
            return [];
        }

        return Json::decode(Context::get('user'));
    }

    public function getAmqp(): Producer
    {
        return make(Producer::class);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
