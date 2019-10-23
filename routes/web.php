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

$router->get('admin/test','Admin\AdminController@test');

$router->group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => 'cross'], function($router) {

    $router->post('login','AuthController@login');

//    $router->group(['middleware' => 'auth:web'], function($router) {

        //管理员
        $router->group(['prefix'=>'administrator/'], function($router) {
            $router->get('','AdminController@index');
            $router->get('show','AdminController@show');
            $router->post('create','AdminController@create');
            $router->post('edit','AdminController@edit');
            $router->post('delete','AdminController@delete');
        });
        //角色
        $router->group(['prefix'=>'role/'], function($router) {
            $router->get('','RoleController@index');
            $router->get('show','RoleController@show');
            $router->post('create','RoleController@create');
            $router->post('edit','RoleController@edit');
            $router->post('delete','RoleController@delete');
        });
        //权限
        $router->group(['prefix'=>'permission/'], function($router) {
            $router->get('','PermissionController@index');
            $router->get('show','PermissionController@show');
            $router->post('create','PermissionController@create');
            $router->post('edit','PermissionController@edit');
            $router->post('delete','PermissionController@delete');
        });


//    });

});

$router->group(['middleware' => ['auth:api','cross']], function($router) {

});
