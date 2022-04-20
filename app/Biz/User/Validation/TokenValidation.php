<?php

declare(strict_types=1);

namespace App\Biz\User\Validation;

use App\Core\Validation\BaseValidation;

class TokenValidation extends BaseValidation
{
    protected $rules = [
        'userName' => 'required',
        'password' => 'required',
    ];

    protected $scene = [
        'jwt' => ['userName', 'password'],
    ];
}
