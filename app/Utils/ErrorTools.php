<?php
/*
 * Sunny 2021/11/23 下午3:35
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Utils;

class ErrorTools extends App
{
    public static function generateErrorInfo(\Exception $error): array
    {
        return [
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'message' => $error->getMessage(),
            'code' => $error->getCode(),
            'trace' => $error->getTraceAsString(),
        ];
    }
}
