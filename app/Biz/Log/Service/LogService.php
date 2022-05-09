<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);
/*
 * Sunny 2021/3/15 下午5:35
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

namespace App\Biz\Log\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

interface LogService extends LoggerInterface
{
    public const GRAY_LOG_LEVELS = [
        // EMERGENCY (600): 系统不可用。
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT => 1,
        // CRITICAL (500): 严重错误。
        LogLevel::CRITICAL => 2,
        // ERROR (400): 运行时错误，但是不需要立刻处理。
        LogLevel::ERROR => 3,
        // WARNING (300): 出现非错误的异常。
        LogLevel::WARNING => 4,
        // NOTICE (250): 普通但是重要的事件。
        LogLevel::NOTICE => 5,
        // INFO (200): 关键事件。
        LogLevel::INFO => 6,
        // DEBUG (100): 详细的debug信息。
        LogLevel::DEBUG => 7,
    ];

    public function emergency($message, array $context = [], $sendGaryLog = true): void;

    public function alert($message, array $context = [], $sendGaryLog = true): void;

    public function critical($message, array $context = [], $sendGaryLog = true): void;

    public function error($message, array $context = [], $sendGaryLog = true): void;

    public function warning($message, array $context = [], $sendGaryLog = true): void;

    public function notice($message, array $context = [], $sendGaryLog = true): void;

    public function info($message, array $context = [], $sendGaryLog = true): void;

    public function debug($message, array $context = [], $sendGaryLog = true): void;

    public function log($level, $message, array $context = [], $sendGaryLog = true): void;

    public function requestLog($request, $response, $responseText = []): void;

    public function createGraylog($level, $message, array $context = []): void;
}
