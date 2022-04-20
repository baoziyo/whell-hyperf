<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Formatter;

class LineFormatter extends \Monolog\Formatter\LineFormatter
{
    public const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        return parent::format($record);
    }
}
