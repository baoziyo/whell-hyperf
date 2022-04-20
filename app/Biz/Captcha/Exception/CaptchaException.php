<?php
/*
 * Sunny 2021/3/10 下午2:22
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Captcha\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class CaptchaException extends BaseErrorException
{
    /**
     * @Tip("验证码不存在.")
     */
    public const CAPTCHA_EMPTY = 500001000;
}
