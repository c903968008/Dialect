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
$router->get('add','Controller@add');
$router->group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => ['auth:web','cross']], function($router) {

//    $router->get('');

});

$router->group(['middleware' => ['auth:api','cross']], function($router) {

});
