<?php
/*
 * Sunny 2021/11/30 下午4:43
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Service;

use App\Biz\Role\Dao\RoleDaoImpl;
use App\Core\Biz\Service\BaseService;

interface RoleService extends BaseService
{
    public function get(int $id): RoleDaoImpl;
}
