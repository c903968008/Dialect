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
$router->get('',function (){
    return 'lumen';
});

$router->group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => 'cross'], function($router) {

    $router->post('login','AuthController@login');
    $router->post('logout','AuthController@logout');

    $router->group(['middleware' => 'auth:web'], function($router) {

        $router->get('get_info','AdminController@getInfo');

        //首页
        $router->group(['prefix'=>'dashboard/'], function($router) {
            $router->get('count','DashboardController@count');
            $router->get('rank','DashboardController@rank');
            $router->get('district/rank','DashboardController@rankByDistrict');
        });

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

        //用户
        $router->group(['prefix'=>'user/'], function($router) {
            $router->get('','UserController@index');
            $router->get('show','UserController@show');
            $router->post('create','UserController@create');
            $router->post('edit','UserController@edit');
            $router->post('delete','UserController@delete');
        });

        //地区
        $router->group(['prefix'=>'district/'], function($router) {
            $router->get('','DistrictController@index');
            $router->get('show','DistrictController@show');
            $router->post('create','DistrictController@create');
            $router->post('edit','DistrictController@edit');
            $router->post('delete','DistrictController@delete');
            $router->get('list','DistrictController@list');
        });

        //反馈
        $router->group(['prefix'=>'feedback/'], function($router) {
            $router->get('','DistrictController@index');
            $router->post('delete','DistrictController@delete');
        });

        //方言
        $router->group(['prefix'=>'dialect/'], function($router) {
            $router->get('','DialectController@index');
            $router->get('show','DialectController@show');
            $router->post('create','DialectController@create');
            $router->post('edit','DialectController@edit');
            $router->post('delete','DialectController@delete');
            $router->get('list','DialectController@list');
            $router->post('audit','DialectController@audit');
        });

    });

});

$router->group(['middleware' => ['cross']], function($router) {
    $router->post('login','AuthController@login');
//    $router->group(['middleware' => ['auth:api']], function($router) {

        //用户
        $router->group(['prefix'=>'user/'], function($router) {
            $router->get('count','UserController@count');       //用户界面的各个需计数的数据
        });

        //问题
        $router->group(['prefix'=>'question/'], function($router) {
            $router->post('create','QuestionController@create');
        });

//    });
});
