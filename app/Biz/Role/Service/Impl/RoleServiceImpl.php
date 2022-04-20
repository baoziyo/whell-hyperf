<?php
/*
 * Sunny 2021/11/30 下午4:43
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Service\Impl;

use App\Biz\Role\Dao\RoleDaoImpl;
use App\Biz\Role\Exception\RoleException;
use App\Biz\Role\Service\RoleService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class RoleServiceImpl extends BaseServiceImpl implements RoleService
{
    protected $dao = RoleDaoImpl::class;

    public function get(int $id): RoleDaoImpl
    {
        $role = RoleDaoImpl::findFromCache($id);

        if (!$role || !$role->exists) {
            throw new RoleException(RoleException::NOT_FOUND);
        }

        return $role;
    }

    public function isPermission($roleId, $name): void
    {
        $role = $this->get($roleId);

    }

    private function getMenus(): array
    {
        $adminMenu = $this->biz->config->get('admin_menu', []);
        $adminMenu = array_column($adminMenu, null, 'code');
        $menu = $this->biz->config->get('menu', []);
        $menu = array_column($menu, null, 'code');
        return ['adminMenu' => $adminMenu, 'menu' => $menu];
    }
}
