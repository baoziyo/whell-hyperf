<?php
/*
 * Sunny 2022/4/20 下午4:09
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

    public function isPermission(int $roleId, string $uri): void;
}
