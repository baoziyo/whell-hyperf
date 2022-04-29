<?php
/*
 * Sunny 2021/11/24 下午8:11
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Type\Token;

use App\Biz\User\Config\TokenStrategy;
use App\Biz\User\Exception\TokenException;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key as FirebaseKey;
use Firebase\JWT\SignatureInvalidException;
use Hyperf\HttpServer\Contract\RequestInterface;

class Jwt implements TokenStrategy
{
    public const ALG = 'HS256';

    public const LEEWAY = 30;

    public function generateToken(array $params = []): array
    {
        $this->checkConfig();

        $time = time();
        $payload = [
            'iss' => env('APP_NAME'),
            'aud' => env('APP_NAME'),
            //签发时间
            'iat' => $time,
            //过期时间
            'exp' => $time + self::EXPIRES_TIME,
            'data' => array_merge($params, ['type' => 'onlyValidate']),
        ];

        $jwt = FirebaseJwt::encode($payload, env('JWT_KEY'), self::ALG);
        $payload['data']['type'] = 'onlyRefresh';
        $payload['exp'] = $time + self::REFRESH_EXPIRES_TIME;
        $refreshJwt = FirebaseJwt::encode($payload, env('JWT_KEY'), self::ALG);

        return [
            'token' => $jwt,
            'refreshToken' => $refreshJwt,
        ];
    }

    public function refreshToken($refreshToken): array
    {
        $this->checkConfig();
        $info = $this->encode($refreshToken);

        return $this->generateToken($info);
    }

    public function validate(RequestInterface $request): array
    {
        $this->checkConfig();
        $token = $request->getHeaderLine('Token', '');
        if (empty($token)) {
            throw new TokenException(TokenException::TOKEN_EMPTY);
        }

        return $this->encode($token);
    }

    private function checkConfig(): void
    {
        if (empty(env('JWT_KEY'))) {
            throw new TokenException(TokenException::JWT_KEY_EMPTY);
        }
    }

    private function encode($token): array
    {
        try {
            FirebaseJwt::$leeway = self::LEEWAY;
            $decode = FirebaseJwt::decode($token, new FirebaseKey('JWT_KEY', self::ALG));

            return (array) $decode;
        } catch (SignatureInvalidException $e) {
            //签名不正确
            throw new TokenException(TokenException::TOKEN_ERROR);
        } catch (BeforeValidException $e) {
            //签名在某个时间点之后才可以使用
            throw new TokenException(TokenException::TOKEN_ERROR);
        } catch (ExpiredException $e) {
            // token过期
            throw new TokenException(TokenException::TOKEN_EXPIRED);
        } catch (Exception $e) {
            // 其他错误
            throw new TokenException(TokenException::TOKEN_ERROR);
        }
    }
}
