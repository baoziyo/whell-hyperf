<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Service\Impl;

use App\Biz\Role\Dao\RoleDaoImpl;
use App\Biz\Role\Exception\RoleException;
use App\Biz\Role\Service\RoleRbacNodeService;
use App\Biz\Role\Service\RoleService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class RoleServiceImpl extends BaseServiceImpl implements RoleService
{
    protected $dao = RoleDaoImpl::class;

    public function get(int $id): RoleDaoImpl
    {
        /** @var RoleDaoImpl $role */
        $role = RoleDaoImpl::findFromCache($id);

        if (! $role || ! $role->exists) {
            throw new RoleException(RoleException::NOT_FOUND);
        }

        return $role;
    }

    public function isPermission(int $roleId, string $uri): void
    {
        $role = $this->get($roleId);
        $rbacNodes = $this->getRbacNodeService()->findByCache($role->data);
        $rbacNodeLinks = array_column($rbacNodes, null, 'link');
        if (! in_array($uri, $rbacNodeLinks, true)) {
            throw new RoleException(RoleException::NOT_FOUND);
        }
    }

    private function getRbacNodeService(): RoleRbacNodeService
    {
        return $this->biz->getService('Role:RoleRbacNode');
    }
}
