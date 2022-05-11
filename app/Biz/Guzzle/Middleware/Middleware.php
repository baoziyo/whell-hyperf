<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Guzzle\Middleware;

use App\Biz\Log\Amqp\Producer\CreateGraylogProducer;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\MessageFormatterInterface;
use GuzzleHttp\Promise as P;
use GuzzleHttp\Promise\PromiseInterface;
use Hyperf\Amqp\Producer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Middleware
{
    public static function log(LoggerInterface $logger, object $formatter, string $logLevel = 'info'): callable
    {
        if (! $formatter instanceof MessageFormatterInterface) {
            throw new \LogicException(sprintf('Argument 2 to %s::log() must be of type %s', self::class, MessageFormatterInterface::class));
        }

        return static function (callable $handler) use ($logger, $formatter, $logLevel): callable {
            return static function (RequestInterface $request, array $options = []) use ($handler, $logger, $formatter, $logLevel) {
                return $handler($request, $options)->then(
                    static function ($response) use ($logger, $request, $formatter, $logLevel): ResponseInterface {
                        $message = $formatter->format($request, $response);
                        $logger->log($logLevel, $message);

                        $messageProducer = new CreateGraylogProducer($logLevel, '记录guzzle日志', [
                            'hostname' => gethostname(),
                            'headers' => $request->getHeaders(),
                            'time' => date('Y-m-d H:i:s'),
                            'method' => $request->getMethod(),
                            'urlTarget' => $request->getRequestTarget(),
                            'code' => $response ? $response->getStatusCode() : 'NULL',
                            'requestBody' => $request->getBody()->__toString(),
                            'responseBody' => $response->getBody()->__toString(),
                        ]);
                        make(Producer::class)->produce($messageProducer);

                        return $response;
                    },
                    static function ($reason) use ($logger, $request, $formatter): PromiseInterface {
                        $response = $reason instanceof RequestException ? $reason->getResponse() : null;
                        $message = $formatter->format($request, $response, P\Create::exceptionFor($reason));
                        $logger->error($message);

                        $body = $response->getBody();
                        if (! $response instanceof ResponseInterface) {
                            $result = 'NULL';
                        } elseif (! $body->isSeekable()) {
                            $result = 'RESPONSE_NOT_LOGGEABLE';
                        } else {
                            $result = $response->getBody()->__toString();
                        }

                        $messageProducer = new CreateGraylogProducer('error', '记录guzzle失败日志', [
                            'hostname' => gethostname(),
                            'headers' => $request->getHeaders(),
                            'time' => date('Y-m-d H:i:s'),
                            'method' => $request->getMethod(),
                            'urlTarget' => $request->getRequestTarget(),
                            'code' => $response->getStatusCode(),
                            'requestBody' => $request->getBody()->__toString(),
                            'responseBody' => $result,
                        ]);
                        make(Producer::class)->produce($messageProducer);

                        return P\Create::rejectionFor($reason);
                    }
                );
            };
        };
    }
}
