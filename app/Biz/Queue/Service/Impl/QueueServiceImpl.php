<?php
/*
 * Sunny 2022/4/20 下午4:40
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Config\QueueStrategy;
use App\Biz\Queue\Dao\QueueDaoImpl;
use App\Biz\Queue\Dao\QueueFailDaoImpl;
use App\Biz\Queue\Exception\QueueException;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Hyperf\DbConnection\Db;
use Throwable;

class QueueServiceImpl extends BaseServiceImpl implements QueueService
{
    protected $dao = QueueDaoImpl::class;

    public function send(string $queueType, array $sendTypes, string $templateType, array $userIds = [], array $params = [], int $delay = 0)
    {
        $sendFails = [];
        if (! $this->getQueueStrategy($queueType)->beforeSendValidateQueue()) {
            throw new QueueException(QueueException::QUEUE_TYPE_DISABLED, null, null, [$queueType]);
        }

        foreach ($sendTypes as $sendType) {
            /** @var QueueDaoImpl $queue */
            $queue = $this->create([
                'queue' => $queueType,
                'type' => $sendType,
                'template' => $templateType,
                'params' => $params,
                'sendUserIds' => $userIds,
                'delay' => $delay,
            ]);

            $params['id'] = $queue->id;

            if (! $this->getQueueStrategy($queueType)->producer($sendType, $templateType, $params, $delay)) {
                $sendFails[] = $templateType;
            }
        }

        if (! empty($sendFails)) {
            return $sendFails;
        }

        return true;
    }

    public function failed(int $id, array $failUserIds, array $failDetails): bool
    {
        /** @var QueueDaoImpl $queue */
        $queue = $this->getByCache($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, null, null, [$id]);
        }

        if ($failUserIds === ['all']) {
            $failUserIds = $queue->sendUserIds;
        }

        $queue->fill(['status' => parent::FAILED]);

        $queueFailId = QueueFailDaoImpl::query()->where('targetId', $id)->value('id');
        if ($queueFailId === '') {
            $queueFail = new QueueFailDaoImpl();
            $queueFail->fill([
                'id' => $id,
                'failUserIds' => $failUserIds,
                'failDetails' => [$failDetails],
            ]);
        } else {
            /** @var QueueFailDaoImpl $queueFail */
            $queueFail = QueueFailDaoImpl::getByCache($queueFailId);
            $queueFail->increment('sendCount');
            $queueFail->fill([
                'failDetails' => array_merge($queueFail->failDetails, $failDetails),
                'failUserIds' => $failUserIds,
            ]);
        }

        Db::beginTransaction();
        try {
            $queueFail->save();
            $queue->save();
            Db::commit();

            return true;
        } catch (Throwable $e) {
            Db::rollBack();
            $this->getLogService()->error('队列保存执行失败信息错误', ErrorTools::generateErrorInfo($e));

            return false;
        }
    }

    public function finished(int $id): bool
    {
        /** @var QueueDaoImpl $queue */
        $queue = $this->getByCache($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, null, null, [$id]);
        }

        $queue->fill(['status' => parent::FINISHED]);
        $queue->save();

        return true;
    }

    public function getNotSendUserIds(int $id): array
    {
        /** @var QueueDaoImpl $queue */
        $queue = $this->getByCache($id);
        /** @var QueueFailDaoImpl $queueFail */
        $queueFail = QueueFailDaoImpl::getByCache($id);
        if ($queue && $queueFail) {
            return array_diff($queue->sendUserIds, $queueFail->failUserIds);
        }

        return [];
    }

    private function getQueueStrategy(string $type): QueueStrategy
    {
        if (! isset(self::QUEUE_STRATEGY_TYPE[$type])) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE);
        }

        return make(self::QUEUE_STRATEGY_TYPE[$type]);
    }
}
