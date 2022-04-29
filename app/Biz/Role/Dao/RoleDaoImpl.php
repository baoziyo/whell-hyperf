<?php
/*
 * Sunny 2021/11/30 下午4:45
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $name
 * @property string $status
 * @property array $data
 * @property string $createdTime
 * @property string $updatedTime
 * @property string $deletedTime
 */
class RoleDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;

    protected $table = 'role';

    protected $fillable = [
        'id', 'name', 'status', 'data',
    ];

    protected $casts = [
        'id' => 'integer',
        'data' => 'array',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}
