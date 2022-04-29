<?php
/*
 * Sunny 2021/11/24 下午7:25
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Biz\User\Service\UserService;
use App\Controller\AbstractController;

class RegisterController extends AbstractController
{
    public function post()
    {
        $params = $this->request->post();
        $response = $this->getUserService()->register($params);

        return $this->buildRequest($response);
    }

    private function getUserService(): UserService
    {
        return $this->biz->getService('User:User');
    }
}
