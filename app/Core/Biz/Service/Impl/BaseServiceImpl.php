<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Service\Impl;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use App\Core\Biz\Service\BaseService;
use http\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

class BaseServiceImpl implements BaseService
{
    /**
     * @var Biz
     */
    protected $biz;

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $dao;

    public function __construct(ContainerInterface $container, Biz $biz)
    {
        $this->biz = $biz;
        $this->container = $container;
    }


    public function create(array $params)
    {
        $dao = new $this->dao();
        $dao->fill($params);
        $dao->save();

        return $dao;
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}

