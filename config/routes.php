<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

if (env('APP_ENV') === 'dev') {
    Router::addGroup('/test', function () {
        Router::get('{id}', 'App\Controller\TestController@get');
        Router::get('', 'App\Controller\TestController@search');
        Router::post('', 'App\Controller\TestController@create');
        Router::put('', 'App\Controller\TestController@update');
        Router::patch('', 'App\Controller\TestController@patch');
        Router::delete('', 'App\Controller\TestController@delete');
    });
}

Router::addGroup('', function () {
    require_once 'routes/login.php';
    require_once 'routes/captcha.php';
    require_once 'routes/register.php';
});
