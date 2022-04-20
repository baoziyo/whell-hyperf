<?php
/*
 * Sunny 2022/4/19 上午10:44
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Token\Service\Impl;

use App\Biz\Token\Dao\TokenDaoImpl;
use App\Biz\Token\Service\TokenService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class TokenServiceImpl extends BaseServiceImpl implements TokenService
{
    protected $dao = TokenDaoImpl::class;

    public function getToken(string $key): string
    {
        $token = TokenDaoImpl::where('key', $key)->first();
        if ($token === null) {
            return '';
        }

        if ($token['expiresTime'] > time() || ($token['createdTime'] + $token['expires']) > time()) {
            return '';
        }

        return $token['value'];
    }

    public function updateToken(string $key, string $value, int $expires): bool
    {
        $token = TokenDaoImpl::where('key', $key)->first();
        if ($token === null) {
            $this->createToken($key, $value, $expires);
            return true;
        }

        TokenDaoImpl::where('key', $key)->update([
            'value' => $value,
            'expires' => $expires,
            'expiresTime' => time() + $expires,
            'createdTime' => time(),
        ]);

        return true;
    }

    public function createToken(string $key, string $value, int $expires): TokenDaoImpl
    {
        $dao = new TokenDaoImpl();
        $dao->fill([
            'key' => $key,
            'value' => $value,
            'expires' => $expires,
            'expiresTime' => time() + $expires,
            'createdTime' => time(),
        ]);
        $dao->save();

        return $dao;
    }
}
