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

    protected $dao;

    public function __construct(ContainerInterface $container, Biz $biz)
    {
        $this->biz = $biz;
        $this->container = $container;
    }

    public function create(array $params)
    {
        $dao = new $this->dao();
        $params = ArrayTools::parts($params, $dao->getFillable());
        $dao->fill($params);
        $dao->save();

        return $dao;
    }

    public function getByCache(int $id)
    {
        return $this->dao::findFromCache($id);
    }

    public function findByCache(array $ids)
    {
        return $this->dao::findManyFromCache($ids);
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
