<?php

declare(strict_types=1);

namespace App\Biz\Queue\Dao;

use App\Core\Biz\Dao\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $queue
 * @property string $type
 * @property string $template
 * @property array $params
 * @property array $sendUserIds
 * @property string $status
 * @property int $delay
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 */
class QueueDaoImpl extends BaseDaoImpl
{
    use Snowflake;

    protected $table = 'queue';

    protected $fillable = [
        'id', 'queue', 'type', 'template', 'params', 'sendUserIds', 'status', 'delay', 'createdTime', 'updatedTime',
    ];

    protected $casts = [
        'id' => 'integer',
        'params' => 'array',
        'sendUserIds' => 'array',
        'delay' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
