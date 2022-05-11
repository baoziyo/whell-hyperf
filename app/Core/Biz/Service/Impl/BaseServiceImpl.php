<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Service\Impl;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use App\Core\Biz\Service\BaseService;
use App\Utils\ArrayTools;
use Psr\Container\ContainerInterface;

class BaseServiceImpl implements BaseService
{
    protected Biz $biz;

    protected ContainerInterface $container;

    /* @phpstan-ignore-next-line */
    protected $dao;

    public function __construct(ContainerInterface $container, Biz $biz)
    {
        $this->biz = $biz;
        $this->container = $container;
    }

    /* @phpstan-ignore-next-line */
    public function create(array $params)
    {
        $dao = new $this->dao();
        /* @phpstan-ignore-next-line */
        $params = ArrayTools::parts($params, $dao->getFillable());
        /* @phpstan-ignore-next-line */
        $dao->fill($params);
        /* @phpstan-ignore-next-line */
        $dao->save();

        return $dao;
    }

    /* @phpstan-ignore-next-line */
    public function getByCache(int $id)
    {
        return $this->dao::findFromCache($id);
    }

    /* @phpstan-ignore-next-line */
    public function findByCache(array $ids)
    {
        return $this->dao::findManyFromCache($ids);
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
