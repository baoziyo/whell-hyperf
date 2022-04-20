<?php
/*
 * Sunny 2021/11/24 下午5:37
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Controller;

class TestController extends AbstractController
{
    public function get()
    {
        return $this->buildRequest('hello world!');
    }

    public function search()
    {
        return $this->buildRequest('hello world!');
    }

    public function create()
    {
        return $this->buildRequest('hello world!');
    }

    public function update()
    {
        return $this->buildRequest('hello world!');
    }

    public function patch()
    {
        return $this->buildRequest('hello world!');
    }

    public function delete()
    {
        return $this->buildRequest('hello world!');
    }
}
