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

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function (){
    //admin index
    Route::get('/', ['as' => 'admin', 'uses' => 'Admin\AdminController@index']);

    Route::resource('faq', 'Admin\FaqController');
    Route::resource('plan', 'Admin\PlanController');

    //users operations
    Route::get('/users', 'Admin\UserController@index')->name('users');
    Route::get('/user/{id}', 'Admin\UserController@showUser')->name('show_user');
    Route::match(['get', 'post'],'user/{id}/add_bonus', 'Admin\UserController@accrualsBonus')->name('bonus');
    Route::get('user/status/{id}','Admin\UserController@makeAdmin')->name('setStatusAdmin');
    
    Route::get('/queries', 'Admin\AdminController@withdrawQueries')->name('queries');
    Route::match(['get', 'post'],'/queries/{id}/cancel', 'Admin\UserController@cancelRequest')->name('cancel');
    Route::match(['get', 'post'],'/queries/{id}/done', 'Admin\UserController@doneRequest')->name('done');
    //config
    Route::match(['get', 'post'],'/email', 'Admin\ConfigController@email')->name('email');
    Route::match(['get', 'post'],'/referrals', 'Admin\ConfigController@referralLevels')->name('referral');
    Route::match(['get', 'post'],'/payments', 'Admin\ConfigController@payments')->name('payment');

    //statistics
    Route::get('/statistics', 'Admin\AdminController@statistics')->name('statistics');
    Route::match(['get', 'post'],'/stat', 'Admin\AdminController@stat')->name('stat');
  
});

/*************** user ****************************/
Route::group(['prefix' => 'api/user', 'middleware' => 'auth:api'], function(){ 
    Route::post('changeprofile', 'User\UserController@changeprofile');
    Route::post('changepassword', 'User\UserController@changepassword');
});

/*************** support ****************************/
Route::group(['prefix' => 'api/support'], function(){ 
    Route::post('/', 'Support\SupportController@index');
});

/*************** wallet ****************************/
Route::group(['prefix' => 'api/wallet', 'middleware' => 'auth:api'], function(){ 
    Route::post('changewallet', 'Wallet\WalletController@changewallet');
});

/*************** wallet/gettransactional ****************************/
Route::group(['prefix' => 'api/wallet'], function(){ 
    Route::post('/gettransactional', 'Wallet\WalletController@gettransactional');
    Route::post('/statistics', 'Wallet\WalletController@statistics');
});

Route::get('auth/reset?secret={token}&email={email}', 'Auth\ResetPasswordController@showResetForm')->name('password.res');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout');
Route::auth();