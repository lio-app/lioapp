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
Route::get('/home', 'AdminController@dashboard')->name('home');

Route::resource('user', 'Resource\UserResource');
Route::get('user/{id}/history', 'AdminController@transhistory')->name('user.history');
Route::get('/userblock/{id}', 'AdminController@userblock')->name('userblock');

Route::get('/userallblock', 'AdminController@userallblock')->name('userallblock');

Route::get('user/{id}/coins', 'AdminController@coins')->name('user.coins');

Route::get('history', 'AdminController@history')->name('history');
Route::get('history/success/{id}', 'AdminController@historySuccess')->name('history.success');
Route::get('history/failed/{id}', 'AdminController@historyFailed')->name('history.failed');

Route::get('settings/index', 'AdminController@settings')->name('settings.index');
Route::post('settings/store', 'AdminController@settings_store')->name('settings.store');

Route::get('settings/payment', 'AdminController@settings_payment')->name('settings.payment');
Route::post('settings/payment', 'AdminController@settings_payment_store')->name('settings.payment.store');

Route::get('profile', 'AdminController@profile')->name('profile');
Route::post('profile', 'AdminController@profile_update')->name('profile.update');

Route::get('password', 'AdminController@password')->name('password');
Route::post('password', 'AdminController@password_update')->name('password.update');

//fiat History
Route::get('fiatHistory', 'AdminController@fiatHistory')->name('fiatHistory');

Route::get('/translation',  'AdminController@translation')->name('translation');

//History
Route::get('history', 'AdminController@history')->name('history');

//Privacy
Route::get('/privacy', 'AdminController@privacy')->name('privacy');
Route::post('/pages', 'AdminController@pages')->name('pages.update');

//Terms
Route::get('/terms', 'AdminController@terms')->name('terms');
Route::post('/termspage', 'AdminController@termspages')->name('terms.update');

//Promocode
Route::resource('promocode', 'PromocodeResource');

//Document Resource
Route::resource('document', 'DocumentResource');

Route::post('userdocument/approve', 'AdminController@userdocument_approve')->name('userdocument.approve');
Route::post('userdocument/reject', 'AdminController@userdocument_reject')->name('userdocument.reject');
Route::get('user/{id}/approve', 'AdminController@approve')->name('user.approve');
Route::get('user/{id}/disapprove', 'AdminController@disapprove')->name('user.disapprove');
Route::get('user/{id}/kycdoc', 'AdminController@kycdoc')->name('user.kycdoc');
Route::get('user/{id}/history', 'AdminController@user_history')->name('user.history');

//withdraw

Route::get('pendingwithdraw', 'AdminController@pendingwithdraw')->name('pendingwithdraw');

Route::get('allwithdraw', 'AdminController@allwithdraw')->name('allwithdraw');

Route::get('history/success/{id}', 'AdminController@historySuccess')->name('history.success');

Route::get('history/failed/{id}', 'AdminController@historyFailed')->name('history.failed');

//Edit coin

Route::post('editcoin', 'AdminController@editcoin')->name('editcoin');

Route::post('savecoin', 'AdminController@savecoin')->name('savecoin');

Route::resource('cointype', 'CoinTypeResource');
Route::get('cointype/{id}/enableStatus', 'CoinTypeResource@enableStatus')->name('cointype.enableStatus');
Route::get('cointype/{id}/disableStatus', 'CoinTypeResource@disableStatus')->name('cointype.disableStatus');

Route::get('currency/index','AdminController@currencyindex')->name('currency.index');
Route::get('currency/add','AdminController@currencyadd')->name('currency.add');
Route::post('currency/store','AdminController@currencystore')->name('currency.store');
Route::get('currency/edit/{id}','AdminController@currencyedit')->name('currency.edit');
Route::post('currency/update','AdminController@currencyupdate')->name('currency.update');

Route::get('news/index', 'NewsController@index')->name('news.index');
Route::get('news/add', 'NewsController@newsadd')->name('news.add');
Route::post('news/store', 'NewsController@newsstore')->name('news.store');
Route::get('news/edit/{id}','NewsController@newsedit')->name('news.edit');
Route::post('news/storeUpdate','NewsController@newsupdate')->name('news.update');
Route::get('news/{id}/enableStatus', 'NewsController@enableStatus')->name('news.enableStatus');
Route::get('news/{id}/disableStatus', 'NewsController@disableStatus')->name('news.disableStatus');
Route::post('news/removeNews', 'NewsController@delete')->name('news.remove');
Route::get('news/sendpush', 'NewsController@sendPushToUser')->name('news.sendpush');
