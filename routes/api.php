<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group([
    'prefix' => 'user',
    'as' => 'users'
], function () use ($router) {
    $router->group(['namespace' => 'Auth'], function () use ($router) {
        $router->post('register', 'RegistrationController');
        $router->post('sign-in', 'SignInController');
        $router->post('recover-password', 'RecoverPasswordController@sendRecoverPassword');
        $router->patch('recover-password/{token}', [
            'uses' => 'RecoverPasswordController@recoverPassword',
            'as' => 'recover-password'
        ]);
    });

    $router->group([
        'prefix' => 'companies',
        'middleware' => 'auth'
    ], function () use ($router) {
        $router->get('/', 'CompanyController@index');
        $router->post('/', 'CompanyController@store');
    });
});
