<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Log\Handler;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Monolog\Logger;
use Monolog\Utils;

class RotatingFileHandler extends \Monolog\Handler\RotatingFileHandler
{
    protected $filenameConfig;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(string $filename, int $maxFiles = 0, $level = Logger::DEBUG, bool $bubble = true, ?int $filePermission = null, bool $useLocking = false)
    {
        parent::__construct($filename);

        $this->filenameConfig = $filename;
        $this->filename = Utils::canonicalizePath($filename);
        $this->maxFiles = $maxFiles;
        $this->nextRotation = new \DateTimeImmutable('tomorrow');
        $this->filenameFormat = '{date}/{filename}';
        $this->dateFormat = static::FILE_PER_DAY;
        $this->mustRotate = true;
        $this->filePermission = $filePermission;
        $this->useLocking = $useLocking;

        $this->setLevel($level);

        if (is_resource($this->getTimedFilename())) {
            $this->stream = $this->getTimedFilename();
        } elseif (is_string($this->getTimedFilename())) {
            $this->url = Utils::canonicalizePath($this->getTimedFilename());
        } else {
            throw new \InvalidArgumentException('A stream must either be a resource or a string.');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        if (! $this->filterSystemLevel($record)) {
            return;
        }
        $this->stream = null;
        if ($record['channel'] === env('APP_NAME')) {
            $this->filename = str_replace('%filename%', strtolower($record['level_name']), Utils::canonicalizePath($this->filenameConfig));
        } else {
            $this->filename = str_replace('%filename%', strtolower($record['channel']), Utils::canonicalizePath($this->filenameConfig));
        }
        $this->url = Utils::canonicalizePath($this->getTimedFilename());

        parent::write($record);
    }

    protected function getTimedFilename(): string
    {
        $fileInfo = pathinfo($this->filename);
        $timedFilename = str_replace(
            ['{date}', '{filename}'],
            [date($this->dateFormat), $fileInfo['filename']],
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );

        if (! empty($fileInfo['extension'])) {
            $timedFilename .= '.' . $fileInfo['extension'];
        }

        return $timedFilename;
    }

    protected function getGlobPattern(): string
    {
        $fileInfo = pathinfo($this->filename);
        $glob = str_replace(
            ['{date}', '{filename}'],
            ['[0-9][0-9][0-9][0-9]*', '*'],
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );
        if (! empty($fileInfo['extension'])) {
            $glob .= '.' . $fileInfo['extension'];
        }

        return $glob;
    }

    protected function rotate(): void
    {
        // update filename
        $this->url = $this->getTimedFilename();
        $this->nextRotation = new \DateTimeImmutable('tomorrow');

        // skip GC of old logs if files are unlimited
        if ($this->maxFiles === 0) {
            return;
        }

        $logFiles = glob($this->getGlobPattern());
        if ($this->maxFiles >= count($logFiles)) {
            // no files to remove
            return;
        }

        // Sorting the files by name to remove the older ones
        usort($logFiles, function ($a, $b) {
            return strcmp($b, $a);
        });

        $dirs = [];
        foreach ($logFiles as $file) {
            $dirs[] = pathinfo($file)['dirname'];
        }
        $dirs = array_unique($dirs);

        foreach (array_slice($dirs, $this->maxFiles) as $dir) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_writable($file)) {
                    // suppress errors here as unlink() might fail if two processes
                    // are cleaning up/rotating at the same time
                    set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
                        return false;
                    });
                    unlink($file);
                    $path = pathinfo($file);
                    if (count(glob($path['dirname'] . '/*')) === 0) {
                        rmdir($path['dirname']);
                    }
                    restore_error_handler();
                }
            }
        }

        $this->mustRotate = false;
    }

    private function filterSystemLevel($record)
    {
        if (isset($record['channel']) && $record['channel'] === 'system') {
            $levelConfig = $this->config->get(StdoutLoggerInterface::class, ['log_level' => []]);
            if (! in_array(strtolower($record['level_name']), $levelConfig['log_level'], true)) {
                return false;
            }
        }

        return true;
    }
}
