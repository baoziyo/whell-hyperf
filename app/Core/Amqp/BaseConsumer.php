<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Amqp;

use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Container\Biz;
use App\Utils\ErrorTools;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Di\Annotation\Inject;
use PhpAmqpLib\Message\AMQPMessage;

abstract class BaseConsumer extends ConsumerMessage
{
    /**
     * @Inject
     * @var Biz
     */
    protected $biz;

    /**
     * @param $data
     * @return array[id,failUserIds,failDetails,...]
     */
    abstract public function handle($data, AMQPMessage $message): array;

    public function consumeMessage($data, AMQPMessage $message): string
    {
        try {
            $data['sendUserIds'] = $this->getQueueService()->getNotSendUserIds($data['id']);
            $response = $this->handle($data, $message);

            if (! empty($response['failUserIds'])) {
                $this->getQueueService()->failed($response['id'], $response['failUserIds'], $response['failDetails']);

                return Result::REQUEUE;
            }

            $this->getQueueService()->finished($response['id']);

            return Result::ACK;
        } catch (\Exception $e) {
            $this->getQueueService()->failed($data['id'], ['all'], ErrorTools::generateErrorInfo($e));

            return Result::REQUEUE;
        }
    }

    public function isEnable(): bool
    {
        return (bool) env('QUEUE_AMQP', false);
    }

    private function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }
}
