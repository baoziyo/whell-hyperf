<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class DefaultAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->getUserService()->register([
            'name' => 'admin',
            'password' => 'admin',
            'isAdmin' => \App\Core\Biz\Service\BaseService::ENABLED,
            'source' => \App\Biz\User\Service\UserService::USER_SOURCE_ORIGINAL,
        ]);
    }

    private function getUserService(): App\Biz\User\Service\UserService
    {
        return make(\App\Core\Biz\Container\Biz::class)->getService('User:User');
    }
}
