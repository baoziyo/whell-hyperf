<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Amqp\Consumer;

use App\Biz\Log\Service\LogService;
use App\Core\Amqp\BaseConsumer;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Result;
use Hyperf\Retry\Annotation\Retry;
use Hyperf\Utils\Codec\Json;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @Consumer(exchange="createGraylog", routingKey="createGraylog", queue="log", name="CreateGraylog", nums=1)
 */
class CreateGraylogConsumer extends BaseConsumer
{
    /**
     * @Retry(base=1000, maxAttempts=3)
     * @param $data
     */
    public function handle($data, AMQPMessage $message): string
    {
        $data = Json::decode($data);

        $this->getLogService()->createGraylog($data['level'], $data['message'], $data['context']);

        return Result::ACK;
    }

    private function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
