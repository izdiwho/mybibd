<?php
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'Controller@welcome');
Route::get('/home', 'BIBDController@getHome');

Route::get('/vcard/pin', 'BIBDController@getPin');
Route::get('/vcard/history/{accNo}', 'BIBDController@getVcardHistory');
Route::post('/vcard/send', 'BIBDController@postVCardSend')->name('vcard.send');
Route::post('/vcard/topup', 'BIBDController@postVcardTopUp')->name('vcard.topup');
Route::post('/vcard/checkusername', 'BIBDController@postVcardCheckPhoneNumber');
Route::get('/vcard/scan/{data}', 'BIBDController@getScannedData');

Route::get('/savings/history/{accNo}', 'BIBDController@getSavingsHistory');