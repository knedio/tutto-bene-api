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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// global prefix version
$version = 'v1';

// 
Route::group([
    'middleware'    => ['json.response'],
    'prefix'        => $version,
    'namespace'     => 'Api',
], function () {

    // Authentication Module
	Route::group([
		'namespace' => 'Auth',
	], function(){
		Route::group([
			'prefix' => 'auth',
		], function(){
            Route::post('forgot-password', 'ForgotPasswordController');
            Route::post('reset-password', 'ResetPasswordForgotPasswordController');
            Route::post('login', 'LoginController');
            Route::post('logout', 'LogoutController');
            Route::post('register', '\App\Http\Controllers\Api\User\UserController@store');
		});
		Route::resource('auth', 'AuthController');
	});
	
	// User Module
	Route::group([
		'middleware' => [
			'auth:api',
		],
		'namespace' => 'User',
	], function(){
		Route::group([
			'prefix' => 'user',
		], function(){
            Route::patch('reset-password/{user}', 'ResetPasswordToDefaultController');
            Route::patch('change-password/{user}', 'ChangePasswordController');
            Route::get('get-by-serial-no', 'GetBySerialNo');
		});
		Route::resource('user', 'UserController');
	});
	
	// Role Module
	Route::group([
		'middleware' => [
			'auth:api',
		],
		'namespace' => 'Role',
	], function(){
		Route::group([
			'prefix' => 'role',
		], function(){

        });
		Route::resource('role', 'RoleController');
	});
	
	// UserReport Module
	Route::group([
		'middleware' => [
			'auth:api',
		],
		'namespace' => 'UserReport',
	], function(){
		Route::group([
			'prefix' => 'user-report',
		], function(){
            Route::get('get-by-user', 'GetByUserController');
		});
		Route::resource('user-report', 'UserReportController');
	});
	
	// User Quarantine Module
	Route::group([
		'middleware' => [
			'auth:api',
		],
		'namespace' => 'UserQuarantine',
	], function(){
		Route::group([
			'prefix' => 'user-quarantine',
		], function(){
		});
		Route::resource('user-quarantine', 'UserQuarantineController');
	});
	
	// User Positive Module
	Route::group([
		'middleware' => [
			'auth:api',
		],
		'namespace' => 'UserPositive',
	], function(){
		Route::group([
			'prefix' => 'user-positive',
		], function(){
		});
		Route::resource('user-positive', 'UserPositiveController');
	});
});
