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
    //Complete data profile with user login
    $router->post('profile', 'UserController@storeProfile');
    $router->get('user-login', 'UserController@profile');
    $router->get('profile/{id}', 'UserController@singleProfile');
    $router->put('update-user/{id}', 'UserController@updateUser');
    
    // Matches "/api/v1/users
    $router->get('users', 'UserController@allUsers');

    // Instruktur 
    $router->post('add-instruktur', 'InstrukturController@postInstruktur');

    $router->get('get-instruktur/{id}', 'InstrukturController@getInstruktur');
    $router->get('get-all-instruktur', 'InstrukturController@getAllInstruktur');
    $router->put('update-instruktur/{id}', 'InstrukturController@updateInstruktur');

    //Kursus    
    $router->get('kursus', 'KursusController@index');
    $router->get('kursus/{id}', 'KursusController@detailKursus');
    $router->get('kursus/{idKursus}/{idUser}', 'KursusController@detailKursusSaya');
    $router->post('add-kursus', 'KursusController@postKursus');
    $router->put('update-kursus/{id}', 'KursusController@updateKursus');

    //Kursus Saya
    $router->post('add-kursus-saya', 'KursusSayaController@postKursusSaya');
    $router->get('kursus-saya/{id}', 'KursusSayaController@kursusSaya');
    $router->put('update-kursus-saya/{id}', 'KursusSayaController@updateKursusSaya');
    
    //Kelas
    $router->post('add-kelas', 'KelasController@postKelas');
    $router->put('update-kelas/{id}', 'KelasController@updateKelas');

    //Materi
    $router->post('add-materi', 'MateriController@postMateri');
    $router->put('update-materi/{id}', 'MateriController@updateMateri');

    //Komentar
    $router->post('add-komentar', 'KomentarController@postKomentar');
    $router->put('update-komentar/{id}', 'KomentarController@updateKomentar');
    $router->get('komentar/{idKursus}/{idKelas}/{idMateri}', 'KomentarController@getKomentar');

    //Transaksi
    $router->post('add-transaksi', 'TransaksiController@postTransaksi');
    $router->put('update-transaksi/{id}', 'TransaksiController@updateTransaksi');

});

//Endpoint show image instruktur
$router->get('/instruktur/foto/{name}', 'InstrukturController@getFoto');

