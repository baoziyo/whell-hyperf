<?php

declare(strict_types=1);

namespace App\Biz\Queue\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;

/**
 * @property int $id
 * @property \Carbon\Carbon $sendTime
 * @property \Carbon\Carbon $createdTime
 * @property \Carbon\Carbon $updatedTime
 */
class QueueMysqlDaoImpl extends BaseDaoImpl
{
    protected $table = 'queue_mysql';

    protected $fillable = [
        'id', 'sendTime', 'createdTime', 'updatedTime',
    ];

    protected $casts = [
        'id' => 'integer',
        'sendTime' => 'datetime',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
