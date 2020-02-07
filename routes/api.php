<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::get('/' , 'UserApiController@');

Route::post('/signincheck' , 'UserApiController@signincheck');
Route::post('/signin' , 'UserApiController@signin');
Route::post('/signupcheck' , 'UserApiController@signupcheck');
Route::post('/signup' , 'UserApiController@signup');

Route::post('/logout' , 'UserApiController@logout');
Route::post('/verify' , 'UserApiController@verify');
Route::post('/forgot/password', 'UserApiController@forgot_password');
Route::post('/reset/password',  'UserApiController@reset_password');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/index' , 'UserApiController@index');
    
    Route::get('/privatekey' , 'UserApiController@privatekey');
    Route::post('/sendcoin' , 'UserApiController@sendcoin');
    Route::post('/sendcointest' , 'UserApiController@sendcointest');
    Route::get('/receive' , 'UserApiController@receive');
    Route::get('/history' , 'UserApiController@history');
    Route::post('/appuserpin' , 'UserApiController@appuserpin');
    Route::post('/apppushnote' , 'UserApiController@apppushnote');
    Route::get('/2fa/enable', 'Google2FAController@enableTwoFactorapi');
    Route::get('/2fa/disable', 'Google2FAController@disableTwoFactorapi');
    Route::post('/g2fotpcheckenable', 'Google2FAController@g2fotpcheckenable');
    //Route::post('/gfaenablereal', 'Google2FAController@postValidateToken');
    Route::post('/gfavalidateotp', 'Google2FAController@gfavalidateotp');
    Route::get('/help' , 'UserApiController@help_details');
    Route::get('/support','UserApiController@support');
    Route::get('/currencylist' , 'UserApiController@currencylist');
    Route::get('/cryptocurrencylist' , 'UserApiController@cryptocurrencylist');
    Route::post('/network','UserApiController@network');
    Route::get('/selectcurrency/{fiat}','UserApiController@selectcurrency');
    //Route::get('/coinselected','UserApiController@coinselected');
    Route::post('/selectcoin' , 'UserApiController@selectcoin');
    Route::post('/selectlanguage' , 'UserApiController@selectlanguage');
    Route::get('/keygen' , 'UserApiController@keygen');

});
