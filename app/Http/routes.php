<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
if (in_array(env('APP_ENV'), ['dev', 'production'])) {
    URL::forceSchema('https');
}

Route::post('/admin/hours/pdf', 'AdminHoursController@pdf');

Route::post('/ajaxedittime', 'AdminHoursController@ajaxEditTime');
Route::post('/ajaxeditovertime', 'AdminHoursController@ajaxEditOvertime');
Route::post('/ajaxeditholiday', 'AdminHoursController@ajaxEditHoliday');
Route::post('/ajaxeditjobs', 'AdminJobController@ajaxEditJob');

Route::get('/auth/login', [ 'as' => 'auth.login', 'uses' => 'Auth\LoginController@getLogin']);
Route::post('/auth/login', [ 'as' => 'auth.login.post', 'uses' => 'Auth\LoginController@postLogin']);
Route::get('/auth/logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@getLogout']);
Route::get('/admin/auth/login', [ 'as' => 'admin.auth.login', 'uses' => 'Auth\LoginController@getAdminLogin']);
Route::post('/admin/auth/login', [ 'as' => 'admin.auth.login.post', 'uses' => 'Auth\LoginController@postAdminLogin']);

Route::get('/admin', 'AdminUsersController@index');
Route::resource('/admin/users', 'AdminUsersController');
Route::resource('/admin/operatives', 'AdminOperativesController');
Route::resource('/admin/hours', 'AdminHoursController');

Route::post('/admin/hours/approve', 'AdminHoursController@approve');
Route::post('/admin/hours/unapprove', 'AdminHoursController@unapprove');

Route::post('/admin/hours', 'AdminHoursController@index');

Route::get('/admin/addoperative', 'AdminHoursController@addOperative');
Route::post('/admin/addoperative', 'AdminHoursController@processAddOperative');

Route::resource('/admin/jobs', 'AdminJobController');

Route::post('/admin/payment/operativecsv', 'AdminPaymentController@operativeCsv');
Route::post('/admin/payment/paymentcsv', 'AdminPaymentController@paymentCsv');

Route::resource('/admin/payment', 'AdminPaymentController');
Route::post('/admin/payment', 'AdminPaymentController@index');
Route::get('/admin/payment/{user_id}', 'AdminPaymentController@show');
Route::post('/admin/payment/{user_id}', 'AdminPaymentController@show');

Route::post('/signin', 'WebController@signIn');
Route::post('/viewdate', 'WebController@changeDate');
Route::post('/edittimes', 'WebController@editTimes');
Route::post('/addjob', 'WebController@processAddJob');

Route::get('/', 'WebController@index');
Route::get('/addjob', 'WebController@addJob');
Route::get('/{date}', 'WebController@showDate');
