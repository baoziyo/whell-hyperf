<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);
/*
 * Sunny 2021/3/11 下午4:22
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

namespace App\Annotation\ResponseFilter;

use BadMethodCallException;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class ResponseFilterAnnotations
{
    /**
     * @var string
     */
    private $class = '';

    /**
     * @var string
     */
    private $mode = 'simple';

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(str_replace('_', '', $key));
            if (! method_exists($this, $method)) {
                throw new BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, get_class($this)));
            }
            $this->{$method}($value);
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}
