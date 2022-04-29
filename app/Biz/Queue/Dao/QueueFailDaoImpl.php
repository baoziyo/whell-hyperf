<?php

declare(strict_types=1);

namespace App\Biz\Queue\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Carbon\Carbon;

/**
 * @property int $id
 * @property array $failUserIds
 * @property int $sendCount
 * @property array $failDetails
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 */
class QueueFailDaoImpl extends BaseDaoImpl
{
    protected $table = 'queue_fail';

    protected $fillable = [
        'id', 'failUserIds', 'sendCount', 'failDetails', 'createdTime', 'updatedTime',
    ];

    protected $casts = [
        'id' => 'integer',
        'failUserIds' => 'array',
        'sendCount' => 'integer',
        'failDetails' => 'array',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
