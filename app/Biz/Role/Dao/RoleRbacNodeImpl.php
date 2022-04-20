<?php
/*
 * Sunny 2021/11/30 下午4:45
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare (strict_types=1);

namespace App\Biz\Role\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $name
 * @property string $status
 * @property string $link
 * @property string $type
 * @property int $parentId
 * @property string $module
 * @property string $controller
 * @property string $node
 * @property string $option
 * @property string $style
 * @property string $icon
 * @property int $sort
 * @property string $createdTime
 * @property string $updatedTime
 * @property string $deletedTime
 */
class RoleRbacNodeImpl extends BaseDaoImpl
{
    use SoftDeletes, Snowflake;

    protected $table = 'role_rbac_node';

    protected $fillable = [
        'id', 'name', 'status', 'link', 'type', 'parentId', 'module', 'controller', 'node', 'option', 'style', 'icon',
        'sort', 'createdTime', 'updatedTime', 'deletedTime'
    ];

    protected $casts = [
        'id' => 'integer',
        'parentId' => 'integer',
        'sort' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}