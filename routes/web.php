<?php
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'Controller@welcome');
Route::get('/home', 'BIBDController@getHome');

Route::get('/vcard/pin', 'BIBDController@getPin');
Route::get('/vcard/history/{accNo}', 'BIBDController@getVcardHistory');
Route::post('/vcard/send', 'BIBDController@postVCardSend')->name('vcard.send');
Route::post('/vcard/checkusername', 'BIBDController@postVcardCheckUsername');
Route::get('/vcard/scan/{data}', 'BIBDController@getScannedData');






Route::get('/info', 'BIBDController@getAccountInfo');
Route::get('/bank', 'BIBDController@getBankAccountDetails');
Route::get('/user', 'BIBDController@getUserAccountDetails');
Route::get('/send', 'BIBDController@postSendOwnAccount');
Route::get('/send3', 'BIBDController@postSendOtherAccount');
Route::get('/sendVcard', 'BIBDController@postVCardSend');

Route::get('/test', 'BIBDController@test');
Route::get('/testEpay', 'BIBDController@testEpay');
Route::get('/testEpay2', 'BIBDController@testEpay2');
