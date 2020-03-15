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

$router->post('test','Controller@test');

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
            $router->get('list','RoleController@list');
        });

        //权限
        $router->group(['prefix'=>'permission/'], function($router) {
            $router->get('','PermissionController@index');
            $router->get('show','PermissionController@show');
            $router->post('create','PermissionController@create');
            $router->post('edit','PermissionController@edit');
            $router->post('delete','PermissionController@delete');
            $router->get('list','PermissionController@list');
        });

        //用户
        $router->group(['prefix'=>'user/'], function($router) {
            $router->get('','UserController@index');
            $router->get('show','UserController@show');
            $router->post('create','UserController@create');
            $router->post('edit','UserController@edit');
            $router->post('delete','UserController@delete');
        });

        //题目
        $router->group(['prefix'=>'question/'], function($router) {
            $router->get('','QuestionController@index');
            $router->get('show','QuestionController@show');
            $router->post('create','QuestionController@create');
            $router->post('edit','QuestionController@edit');
            $router->post('delete','QuestionController@delete');
        });

        //地区
        $router->group(['prefix'=>'district/'], function($router) {
            $router->get('','DistrictController@index');
            $router->get('show','DistrictController@show');
            $router->post('create','DistrictController@create');
            $router->post('edit','DistrictController@edit');
            $router->post('delete','DistrictController@delete');
            $router->get('list','DistrictController@list');
            $router->get('previous','DistrictController@getPrevious');
        });

        //反馈
        $router->group(['prefix'=>'feedback/'], function($router) {
            $router->get('','FeedbackController@index');
            $router->post('delete','FeedbackController@delete');
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

        //证书
        $router->group(['prefix'=>'certificate/'], function($router) {
            $router->get('','CertificateController@index');
            $router->get('show','CertificateController@show');
            $router->post('create','CertificateController@create');
            $router->post('edit','CertificateController@edit');
            $router->post('delete','CertificateController@delete');
        });

    });

});

$router->group(['middleware' => ['cross']], function($router) {
    $router->post('login','AuthController@login');
    $router->group(['middleware' => ['auth:api']], function($router) {

        //用户
        $router->group(['prefix'=>'user/'], function($router) {
            $router->get('count','UserController@count');       //用户界面的各个需计数的数据
            $router->get('rank','UserController@rank');
            $router->get('show','UserController@show');
        });

        //问题
        $router->group(['prefix'=>'question/'], function($router) {
            $router->post('create','QuestionController@create');        //出题
            $router->post('edit','QuestionController@edit');
            $router->get('show','QuestionController@show');
            $router->post('audio/upload/create','QuestionController@uploadAudioCreate');
            $router->post('audio/upload/edit','QuestionController@uploadAudioEdit');
            $router->get('answer/list','QuestionController@answerList');        //答题列表
            $router->post('answer','QuestionController@answer');        //答题
            $router->get('user/list','QuestionController@userList');        //用户的题
            $router->post('good','QuestionController@good');        //点赞
        });

        //地区
        $router->group(['prefix'=>'district/'], function($router) {
            $router->get('list','DistrictController@list');         //地区列表
            $router->get('plist','DistrictController@listByPid');
        });

        //方言
        $router->group(['prefix'=>'dialect/'], function($router) {
            $router->get('learn','DialectController@getByDistrict');         //学习方言列表
        });

        //反馈
        $router->group(['prefix'=>'feedback/'], function($router) {
            $router->post('create','FeedbackController@create');
            $router->post('status','FeedbackController@status');
            $router->post('accept','FeedbackController@accept');
            $router->get('','FeedbackController@index');
        });

        //证书
        $router->group(['prefix'=>'certificate/'], function($router) {
            $router->get('user/list','CertificateController@userList');
        });

    });
});
