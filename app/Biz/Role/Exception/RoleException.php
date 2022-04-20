<?php
/*
 * Sunny 2021/3/10 下午2:22
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Role\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class RoleException extends BaseErrorException
{
    /**
     * @Tip("找不到该权限组.")
     */
    public const NOT_FOUND = 500002000;
}
