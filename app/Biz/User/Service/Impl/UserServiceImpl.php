<?php
/*
 * Sunny 2021/11/26 上午11:43
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\User\Service\Impl;

use App\Biz\User\Config\UserSourceStrategy;
use App\Biz\User\Dao\UserDaoImpl;
use App\Biz\User\Exception\UserException;
use App\Biz\User\Service\UserService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Str;

class UserServiceImpl extends BaseServiceImpl implements UserService
{
    protected $dao = UserDaoImpl::class;

    public function register(array $data): UserDaoImpl
    {
        $data = $this->getUserSourceStrategy($data['source'])->buildRegisterParams($data);
        [$data['password'], $data['salt']] = $this->generatePassword($data['password']);

        Db::beginTransaction();
        try {
            $registerUser = $this->create($data);

            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();

            throw new UserException(UserException::REGISTER_USER_ERROR);
        }

        return $registerUser;
    }

    private function getUserSourceStrategy($type): UserSourceStrategy
    {
        if (! isset(self::USER_SOURCE_STRATEGY_TYPE[$type])) {
            throw new UserException(UserException::TOKEN_TYPE_ERROR);
        }

        return make(self::USER_SOURCE_STRATEGY_TYPE[$type]);
    }

    /**
     * @return array[eg:password,eg:salt]
     */
    private function generatePassword(string $password): array
    {
        $salt = Str::random();

        $password .= '{' . $salt . '}';
        $digest = hash('sha512', $password, true);
        for ($i = 1; $i < 5000; ++$i) {
            $digest = hash('sha512', $digest . $salt, true);
        }

        return [base64_encode($digest), $salt];
    }
}
