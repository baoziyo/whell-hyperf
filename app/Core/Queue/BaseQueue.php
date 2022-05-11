<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Core\Queue;

use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Container\Biz;
use App\Utils\ErrorTools;
use Hyperf\AsyncQueue\Job;
use Hyperf\Di\Annotation\Inject;
use Throwable;

abstract class BaseQueue extends Job
{
    /**
     * @Inject
     */
    protected Biz $biz;

    protected $maxAttempts = 3;

    protected array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @throws Throwable
     */
    public function handle(): bool
    {
        try {
            $this->params['sendUserIds'] = $this->getQueueService()->getNotSendUserIds($this->params['id']);
            $response = $this->process($this->params);

            if (! empty($response['failUserIds'])) {
                $this->getQueueService()->failed($response['id'], $response['failUserIds'], $response['failDetails']);
            } else {
                $this->getQueueService()->finished($response['id']);
            }
        } catch (Throwable $e) {
            $this->getQueueService()->failed($this->params['id'], ['all'], ErrorTools::generateErrorInfo($e));
            throw new $e();
        }

        return true;
    }

    /**
     * @return array[id,failUserIds,failDetails,...]
     */
    abstract public function process(array $params): array;

    private function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }
}
