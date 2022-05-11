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

use App\Core\Biz\Container\Biz;
use App\Utils\ArrayTools;
use Hyperf\Utils\Codec\Json;

abstract class Filter
{
    /**
     * 简化模式.
     */
    public const SIMPLE_MODE = 'simple';

    /**
     * 复杂模式.
     */
    public const COMPLEX_MODE = 'complex';

    protected Biz $biz;

    protected string $mode = self::SIMPLE_MODE;

    protected string $fieldsName = 'fields';

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function filter(string $json): string
    {
        $data = Json::decode($json);

        if ($this->mode === self::SIMPLE_MODE) {
            $data['data'] = $this->simple($data['data']);
        }

        if ($this->mode === self::COMPLEX_MODE) {
            $data['data'] = $this->complex($data['data']);
        }

        return Json::encode($data);
    }

    protected function simpleFields(array $data): array
    {
        return $data;
    }

    protected function complexFields(array $data): array
    {
        return $data;
    }

    private function simple(array $data): array
    {
        $property = $this->mode . 'Fields';
        if (property_exists($this, $this->fieldsName) && $this->{$this->fieldsName}) {
            $data = ArrayTools::parts($data, $this->{$this->fieldsName});
        }

        if (method_exists($this, $property)) {
            $data = $this->{$property}($data);
        }

        return $data;
    }

    private function complex(array $data): array
    {
        $property = $this->mode . 'Fields';
        if (property_exists($this, $this->fieldsName) && $this->{$this->fieldsName}) {
            if (! empty($data['list'])) {
                foreach ($data['list'] as &$item) {
                    $item = ArrayTools::parts($item, $this->{$this->fieldsName});
                    if (method_exists($this, $property)) {
                        $item = $this->{$property}($item);
                    }
                }
            } else {
                foreach ($data as &$item) {
                    $item = ArrayTools::parts($item, $this->{$this->fieldsName});
                    if (method_exists($this, $property)) {
                        $item = $this->{$property}($item);
                    }
                }
            }
        }

        return $data;
    }
}
