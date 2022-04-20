<?php
/*
 * Sunny 2021/11/30 上午11:12
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */


declare(strict_types=1);

namespace App\Biz\Captcha\Service\Impl;

use App\Biz\Captcha\Exception\CaptchaException;
use App\Biz\Captcha\Service\CaptchaService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Baoziyoo\HyperfCaptcha\Captcha;
use Hyperf\Utils\Str;
use Psr\SimpleCache\CacheInterface;

class CaptchaServiceImpl extends BaseServiceImpl implements CaptchaService
{
    public function generateCaptcha(): array
    {
        $captcha = new Captcha();
        $captcha = $captcha->generateCode();
        $key = Str::random(64);

        $cache = $this->container->get(CacheInterface::class);
        $cache->set($key, $captcha['code'], self::TTL);

        return [
            'base64' => $captcha['base64'],
            'key' => $key,
        ];
    }

    public function validatorCode(string $code, string $key): bool
    {
        $cache = $this->container->get(CacheInterface::class);
        if (!$cache->has($key)) {
            throw new CaptchaException(CaptchaException::CAPTCHA_EMPTY);
        }

        return $cache->get($key) === $code;
    }
}