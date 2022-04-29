<?php
/*
 * Sunny 2021/3/10 下午2:22
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class UserException extends BaseErrorException
{
    /**
     * @Tip("类型错误.")
     */
    public const TOKEN_TYPE_ERROR = 500004000;

    /**
     * @Tip("注册用户失败.")
     */
    public const REGISTER_USER_ERROR = 500004001;
}
