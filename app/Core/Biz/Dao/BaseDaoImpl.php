<?php
/*
 * Sunny 2021/11/30 下午5:01
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Dao;

use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\Database\Model\Model;

abstract class BaseDaoImpl extends BaseModel
{
    use Cacheable;

    public const CREATED_AT = 'createdTime';

    public const UPDATED_AT = 'updatedTime';

    public const DELETED_AT = 'deletedTime';

    protected $dateFormat = 'Y-m-d H:i:s';

    public static function getById($id): ?Model
    {
        return self::findFromCache($id);
    }

    public static function findByIds($ids): Collection
    {
        return self::findManyFromCache($ids);
    }
}