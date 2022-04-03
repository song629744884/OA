<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', function () {
    return view('index');
});
Route::prefix('index')->group(function () {
    Route::get('index','Admin\IndexController@index')->middleware('auth');
    Route::get('login','Admin\IndexController@login')->name('index.login');
    Route::post('loginIn','Admin\IndexController@loginIn');
    Route::post('logout','Admin\IndexController@logout');
    Route::post('imgUpload','Admin\IndexController@imgUpload')->middleware('auth');
});

Route::prefix('menu')->middleware('auth')->group(function () {
    Route::get('index','Admin\MenuController@index');
    Route::get('menuList','Admin\MenuController@menuList');
    Route::get('roleMenu','Admin\MenuController@roleMenu');
    Route::get('menuData','Admin\MenuController@menuData');
    Route::get('topMenu','Admin\MenuController@topMenu');
    Route::post('save','Admin\MenuController@save');
    Route::post('delete/{id}','Admin\MenuController@delete');
});
Route::prefix('role')->middleware('auth')->group(function () {
    Route::get('index','Admin\RoleController@index');
    Route::get('data','Admin\RoleController@data');
    Route::post('save','Admin\RoleController@save');
    Route::post('delete/{id}','Admin\RoleController@delete');
    Route::get('menus/{id}','Admin\RoleController@menus');
    Route::post('saveNode','Admin\RoleController@saveNode');
});

Route::prefix('depart')->middleware('auth')->group(function () {
    Route::get('index','Admin\DepartControler@index');
    Route::get('data','Admin\DepartControler@data');
    Route::post('save','Admin\DepartControler@save');
    Route::post('delete/{id}','Admin\DepartControler@delete');
    Route::get('option','Admin\DepartControler@option');
});

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('index','Admin\UserController@index');
    Route::get('data','Admin\UserController@data');
    Route::post('save','Admin\UserController@save');
    Route::post('delete/{id}','Admin\UserController@delete');
    Route::get('me','Admin\UserController@me');
    Route::get('myUserInfo','Admin\UserController@myUserInfo');
    Route::get('password','Admin\UserController@password');
    Route::post('saveMyUserInfo','Admin\UserController@saveMyUserInfo');
    Route::post('passwordSave','Admin\UserController@passwordSave');
});

Route::prefix('article_type')->middleware('auth')->group(function () {
    Route::get('index','Admin\ArticleTypeControler@index');
    Route::get('data','Admin\ArticleTypeControler@data');
    Route::post('save','Admin\ArticleTypeControler@save');
    Route::post('delete/{id}','Admin\ArticleTypeControler@delete');
});

Route::prefix('article')->middleware('auth')->group(function () {
    Route::get('all','Admin\ArticleControler@all');
    Route::get('allData','Admin\ArticleControler@allData');
    Route::get('view','Admin\ArticleControler@view');
    Route::get('index','Admin\ArticleControler@index');
    Route::get('data','Admin\ArticleControler@data');
    Route::get('create','Admin\ArticleControler@create');
    Route::get('read','Admin\ArticleControler@read');
    Route::get('edit','Admin\ArticleControler@edit');
    Route::post('save','Admin\ArticleControler@save');
    Route::post('delete/{id}','Admin\ArticleControler@delete');
});
//Route::get('positionName/index','Ucenter\PositionNameController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
