<?php

declare (strict_types=1);

namespace App\Biz\User\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property int $userId
 * @property string $type
 * @property string $fromId
 * @property string $fromKey
 * @property \Carbon\Carbon $createdTime
 * @property \Carbon\Carbon $updatedTime
 * @property string $deletedTime
 */
class UserBindDaoImpl extends BaseDaoImpl
{
    use SoftDeletes, Snowflake;

    protected $table = 'user_bind';

    protected $fillable = [
        'id', 'userId', 'type', 'fromId', 'fromKey', 'createdTime', 'updatedTime', 'deletedTime'
    ];

    protected $casts = [
        'id' => 'integer',
        'userId' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}