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

namespace App\Listener;

use Hyperf\AsyncQueue\AnnotationJob;
use Hyperf\AsyncQueue\Event\AfterHandle;
use Hyperf\AsyncQueue\Event\BeforeHandle;
use Hyperf\AsyncQueue\Event\Event;
use Hyperf\AsyncQueue\Event\FailedHandle;
use Hyperf\AsyncQueue\Event\RetryHandle;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

/**
 * @Listener
 */
class QueueHandleListener implements ListenerInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    public function __construct(ContainerInterface $container, FormatterInterface $formatter)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('queue');
        $this->formatter = $formatter;
    }

    public function listen(): array
    {
        return [
            // 处理后触发
            AfterHandle::class,
            // 处理前触发
            BeforeHandle::class,
            // 处理失败触发
            FailedHandle::class,
            // 重试处理前触发
            RetryHandle::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof Event && $event->message->job()) {
            $job = $event->message->job();
            $jobClass = get_class($job);
            if ($job instanceof AnnotationJob) {
                $jobClass = sprintf('Queue [%s@%s]', $job->class, $job->method);
            }

            switch (true) {
                case $event instanceof BeforeHandle:
                    $this->logger->info(sprintf('Redis Processing %s.', $jobClass));
                    break;
                case $event instanceof AfterHandle:
                    $this->logger->info(sprintf('Redis Processed %s.', $jobClass));
                    break;
                case $event instanceof FailedHandle:
                    $this->logger->error(sprintf('Redis Failed %s.', $jobClass) . PHP_EOL . $this->formatter->format($event->getThrowable()));
                    break;
                case $event instanceof RetryHandle:
                    $this->logger->warning(sprintf('Redis Retried %s.', $jobClass));
                    break;
            }
        }
    }
}
