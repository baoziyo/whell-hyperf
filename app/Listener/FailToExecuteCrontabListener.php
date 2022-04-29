<?php
/*
 * Sunny 2022/4/28 下午5:51
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Listener;

use App\Core\Biz\Container\Biz;
use App\Utils\ErrorTools;
use Hyperf\Crontab\Event\FailToExecute;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

/**
 * @Listener
 */
class FailToExecuteCrontabListener implements ListenerInterface
{
    /**
     * @var Biz
     */
    protected $biz;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var LoggerFactory
     */
    protected $logger;

    public function __construct(Biz $biz, ContainerInterface $container)
    {
        $this->biz = $biz;
        $this->container = $container;
        $this->logger = $this->container->get(LoggerFactory::class)->get('crontab');
    }

    public function listen(): array
    {
        return [
            FailToExecute::class,
        ];
    }

    /**
     * @param FailToExecute $event
     */
    public function process(object $event): void
    {
        $crontab = $event->crontab->getName();
        $crontabClass = get_class($crontab);
        $this->logger->error(sprintf('Crontab Failed %s.', $crontabClass ?? $crontab), ErrorTools::generateErrorInfo($event->throwable));
    }
}
