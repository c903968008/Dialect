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

$router->group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => 'cross'], function($router) {

    $router->post('login','AuthController@login');

//    $router->group(['middleware' => 'auth:web'], function($router) {

        $router->group(['prefix'=>'administrator/'], function($router) {
            $router->get('','AdminController@index');
            $router->get('show','AdminController@show');
            $router->post('create','AdminController@create');
            $router->post('update','AdminController@update');
            $router->post('delete','AdminController@delete');
        });


//    });

});

$router->group(['middleware' => ['auth:api','cross']], function($router) {

});
