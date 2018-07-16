<?php

use Illuminate\Http\Request;

//registration and login
Route::post('register', 'Auth\RegisterController@register');
Route::post('register?ref_link={ref_link}', 'Auth\RegisterController@register');
Route::post('/login', ['as'   => 'register', 'uses' => '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken',]);
Route::post('/logout', 'User\UserController@logout')->middleware('auth:api');

//reset password
Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//Route::get('password/reset?secret={token}&email={email}', 'Auth\ResetPasswordController@showResetForm')->name('password.res');
Route::get('plans/', 'HomeController@plans');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user/', 'User\UserController@user');                    //data of user
    Route::get('user/referrals/', 'User\UserController@referrals');     //referrals
    Route::get('user/deposits', 'User\UserController@deposits');        //deposits
    Route::get('user/operations', 'User\UserController@operations');    //operations: refill and withdraw
    Route::get('user/wallets/', 'User\UserController@wallets');         //wallets

    Route::post('user/pay_from_account/', 'User\UserController@payFromAccount'); //method for pay from account
    Route::post('user/withdraw/', 'User\UserController@withdraw'); // ...
    Route::post('user/withdraw/cancel/', 'User\UserController@CancelWithdraw'); //...

    //routes for payment systems event
    Route::post('perfect_money/', 'PaymentController@perfectMoney');
    Route::post('advanced_cash/', 'PaymentController@advancedCash');
    Route::post('payeer/', 'PaymentController@payeer');

    Route::post('data/', 'PaymentController@data');
});

