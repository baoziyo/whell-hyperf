<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Service\Impl;

use App\Biz\Role\Dao\RoleRbacNodeDaoImpl;
use App\Biz\Role\Service\RoleRbacNodeService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class RoleRbacNodeServiceImpl extends BaseServiceImpl implements RoleRbacNodeService
{
    protected $dao = RoleRbacNodeDaoImpl::class;
}
