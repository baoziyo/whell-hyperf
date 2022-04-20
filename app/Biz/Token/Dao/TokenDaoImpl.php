<?php

declare (strict_types=1);

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
    protected $table = 'token';

    protected $fillable = [
        'key', 'value', 'expires', 'expiresTime', 'createdTime'
    ];

    protected $casts = [
        'expires' => 'integer',
        'createdTime' => 'datetime',
    ];
}