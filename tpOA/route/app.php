<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');

Route::get('login','index/login');
Route::post('loginIn','index/loginIn');
Route::post('logout','index/logout');
//Route::get('index','index/index')->middleware([\app\middleware\UserCheck::class]);
//Route::get('menuList','index/menuList')->middleware([\app\middleware\UserCheck::class]);
Route::get('index','index/index')->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);
Route::post('imgUpload','index/imgUpload')->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);
//Route::get('menu/menuList','menu/menuList')->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);
Route::group('menu', function(){
    Route::get('menuList','menu/menuList');
    Route::get('index','menu/index');
    Route::get('menuData','menu/menuData');
    Route::get('roleMenuData','menu/roleMenuData');
    Route::post('save','menu/save');
    Route::post('delete/:id','menu/delete');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);

Route::group('role', function(){
    Route::get('index','role/index');
    Route::get('data','role/data');
    Route::get('menus','role/menus');
    Route::post('save','role/save');
    Route::post('delete/:id','role/delete');
    Route::post('menuSave','role/menuSave');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);

Route::group('depart', function(){
    Route::get('index','depart/index');
    Route::get('data','depart/data');
    Route::post('save','depart/save');
    Route::post('delete/:id','depart/delete');
    Route::post('option','depart/option');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);

Route::group('user', function(){
    Route::get('index','user/index');
    Route::get('data','user/data');
    Route::post('save','user/save');
    Route::post('delete/:id','user/delete');
    Route::get('me','user/me');
    Route::get('password','user/password');
    Route::get('myUserInfo','user/myUserInfo');
    Route::post('saveMyUserInfo','user/saveMyUserInfo');
    Route::post('passwordSave','user/passwordSave');
//    Route::post('option','depart/option');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);

Route::group('article', function(){
    Route::get('all','article/all');
    Route::get('allData','article/allData');
    Route::get('view','article/view');
    Route::get('index','article/index');
    Route::get('data','article/data');
    Route::post('save','article/save');
    Route::post('delete/:id','article/delete');
    Route::get('read','article/read');
    Route::get('edit','article/edit');
    Route::get('create','article/create');
//    Route::post('option','depart/option');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);

Route::group('article_type', function(){
    Route::get('index','article_type/index');
    Route::get('data','article_type/data');
    Route::post('save','article_type/save');
    Route::post('delete/:id','article_type/delete');
//    Route::post('option','depart/option');
})->middleware([\app\middleware\UserCheck::class,\app\middleware\CsrfToken::class]);