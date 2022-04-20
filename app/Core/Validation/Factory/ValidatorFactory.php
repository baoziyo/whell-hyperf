<?php

/*
 * Sunny 2021/5/26 上午11:12
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Validation\Factory;

use App\Core\Validation\Validator;
use Hyperf\Contract\ValidatorInterface;

class ValidatorFactory extends \Hyperf\Validation\ValidatorFactory
{
    /**
     * Resolve a new Validator instance.
     */
    protected function resolve(array $data, array $rules, array $messages, array $customAttributes): ValidatorInterface
    {
        if (is_null($this->resolver)) {
            return new Validator($this->translator, $data, $rules, $messages, $customAttributes);
        }

        return call_user_func($this->resolver, $this->translator, $data, $rules, $messages, $customAttributes);
    }
}
