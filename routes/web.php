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

Route::get('/testapi', 'AdminController@testapi');

Route::get('/siteapi/guzzlesaveuser', 'AdminController@guzzlesaveuser');

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});


Route::get('/success', function () {
    return view('success');
});
/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/enable', function () {
    return view('enable');
});
// Route::get('/change_password', function () {
//     return view('change_password');
// });


//change password
  Route::get('/password', function () {
    return view('password');
  });
  Route::post('/change/password', 'HomeController@update_password');


Route::get('/forgot_password', function () {
    return view('forgot_password');
});
Route::get('/security', function () {
    return view('security');
});
Route::get('/support', function () {
    return view('support');
});
Route::get('/transactions', function () {
    return view('transactions');
});
Route::get('/transactions', 'HomeController@history');
Route::get('/page_not_found', function () {
    return view('page_not_found');
});

Route::get('terms', function () {
    $page = 'page_terms';
    $title = 'Terms & Conditions';
    return view('terms', compact('page', 'title'));
});

Route::get('dsgvo/en', 'AdminController@dsgvo_en');
Route::get('dsgvo/gr', 'AdminController@dsgvo_gr');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

Auth::routes();

Route::group(['middleware'=>'auth'], function(){


  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/wallet', 'HomeController@wallet')->name('wallet');
  Route::post('/sendcoin', 'HomeController@sendcoin')->name('sendcoin');
  Route::post('/network','HomeController@network')->name('network');
  Route::get('/networkpage','HomeController@networkpage')->name('networkpage');
  //Google 2 factor 
  Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
  Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
  
  // Web check otp
  Route::post('/g2fotpcheckenable', 'Google2FAController@g2fotpcheckenable');

   Route::get('/tokensellnotify/{response}', 'HomeController@tokensellnotify')->name('tokensellnotify');

});

Route::get('/2fa/validate', 'Auth\LoginController@getValidateToken');
Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\LoginController@postValidateToken']);



// API check otp
//Route::post('/gfaenablereal', 'Google2FAController@postValidateToken');

Route::post('/gfavalidateotp', 'Google2FAController@gfavalidateotp');

