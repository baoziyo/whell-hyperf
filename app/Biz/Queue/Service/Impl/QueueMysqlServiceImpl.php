<?php
/*
 * Sunny 2022/4/20 下午4:40
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Config\BaseMailTemplate;
use App\Biz\Queue\Dao\QueueDaoImpl;
use App\Biz\Queue\Dao\QueueFailDaoImpl;
use App\Biz\Queue\Dao\QueueMysqlDaoImpl;
use App\Biz\Queue\Service\QueueMysqlService;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Throwable;

class QueueMysqlServiceImpl extends BaseServiceImpl implements QueueMysqlService
{
    protected $dao = QueueDaoImpl::class;

    public function producer(int $id, int $delay = 0): bool
    {
        $this->create([
            'id' => $id,
            'sendTime' => time() + $delay,
        ]);

        return true;
    }

    public function consumer(): bool
    {
        $queueMysqlLists = QueueMysqlDaoImpl::query()->where('sendTime', '<=', time())->get();
        if ($queueMysqlLists->isEmpty()) {
            return true;
        }

        $queueMysqlLists->map(function ($queueMysqlList) {
            /** @var QueueMysqlDaoImpl $queueMysqlList */
            /** @var QueueDaoImpl $queue */
            $queue = $this->getQueueService()->getByCache($queueMysqlList->id);
            if ($queue !== null) {
                /** @var BaseMailTemplate $template */
                $template = new $queue->template();

                try {
                    $queue->sendUserIds = $this->getQueueService()->getNotSendUserIds($queue->id);
                    $response = $template->handle($queue->params);

                    if (! empty($response['failUserIds'])) {
                        $this->getQueueService()->failed($response['id'], $response['failUserIds'], $response['failDetails']);
                        $this->failed($response['id']);
                    } else {
                        $this->getQueueService()->finished($response['id']);
                    }
                } catch (Throwable $e) {
                    $this->getQueueService()->failed($queue->id, ['all'], ErrorTools::generateErrorInfo($e));
                    $this->failed($queue->id);
                    throw new $e();
                }
            }
        });

        return true;
    }

    protected function failed(int $id): void
    {
        /** @var QueueMysqlDaoImpl $queueMysql */
        $queueMysql = QueueMysqlDaoImpl::getByCache($id);
        if ($queueMysql === null) {
            return;
        }

        /** @var QueueFailDaoImpl $queueFail */
        $queueFail = QueueFailDaoImpl::getByCache($id);
        if ($queueFail === null) {
            return;
        }

        if ($queueFail->sendCount < 3) {
            return;
        }

        $queueMysql->delete();
    }

    protected function finished(int $id): void
    {
        /** @var QueueMysqlDaoImpl $queueMysql */
        $queueMysql = QueueMysqlDaoImpl::getByCache($id);
        if ($queueMysql === null) {
            return;
        }

        $queueMysql->delete();
    }

    private function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }
}
