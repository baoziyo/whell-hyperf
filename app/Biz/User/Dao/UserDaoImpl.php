<?php
/*
 * Sunny 2021/11/30 下午2:50
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property int $phone
 * @property string $email
 * @property int $role
 * @property string $status
 * @property string $lasLoginTime
 * @property string $createdTime
 * @property string $updatedTime
 * @property string $deletedTime
 */
class UserDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;

    protected $table = 'user';

    protected $fillable = [
        'id', 'name', 'password', 'salt', 'phone', 'email', 'role', 'status', 'lasLoginTime',
    ];

    protected $casts = [
        'id' => 'integer',
        'phone' => 'integer',
        'role' => 'integer',
    ];
}
