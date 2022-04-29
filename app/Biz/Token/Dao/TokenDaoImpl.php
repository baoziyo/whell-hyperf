<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Token\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;

/**
 * @property string $key
 * @property string $value
 * @property int $expires
 * @property string $expiresTime
 * @property \Carbon\Carbon $createdTime
 */
class TokenDaoImpl extends BaseDaoImpl
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected $table = 'token';

    protected $fillable = [
        'key', 'value', 'expires', 'expiresTime', 'createdTime',
    ];

    protected $casts = [
        'expires' => 'integer',
        'createdTime' => 'datetime',
    ];
}
