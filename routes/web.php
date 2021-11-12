<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    // Matches "/api/v1/register
    $router->post('register', 'AuthController@register');

    // Matches "/api/v1/login
    $router->post('login', 'AuthController@login');

    //User
    //Complete profile with user login or admin
    $router->post('add-profile', 'UserController@postProfile');
    //Complete profile with user login
    $router->post('profile', 'UserController@storeProfile');
    $router->get('user-login', 'UserController@profile');
    $router->get('profile/{id}', 'UserController@singleProfile');

    // Matches "/api/v1/users
    $router->get('users', 'UserController@allUsers');

    // Instruktur 
    $router->post('add-instruktur', 'InstrukturController@postInstruktur');

    $router->get('get-instruktur/{id}', 'InstrukturController@getInstruktur');
    $router->get('get-all-instruktur', 'InstrukturController@getAllInstruktur');
    $router->put('update-instruktur/{id}', 'InstrukturController@updateInstruktur');
});

$router->get('/instruktur/avatar/{name}', 'InstrukturController@get_avatar');
