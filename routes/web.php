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

// -- Common Routes
{
    Route::get('/', 'Common\HomeController@index');
    Route::get('download/{id}', 'Common\DocumentController@download')->middleware('auth')->name('document.download');
    Route::post('api/document/destroy', 'Common\DocumentController@destroy')->middleware('auth')->name('document.destroy');

    // Authentication Routes
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    // Blocked users
    Route::get('inactive', 'Common\UserController@blocked_page')->name('user.blocked_page');

    // Account & Profile Routes
    Route::group(['middleware' => ['auth']], function () {
        Route::get('account', 'Common\UserController@index')->name('account');
        Route::post('account', 'Common\UserController@update');
        Route::get('profile/{id}', 'Common\UserController@show')->name('profile');
    });

    // Notification Routes
    Route::group(['middleware' => ['auth']], function () {
        Route::get('notification', 'Common\NotificationController@index')->name('notification.index');
        Route::get('notification/{id}', 'Common\NotificationController@show')->name('notification.show');
        Route::post('notification/mark/read', 'Common\NotificationController@markRead')->name('notification.mark.read');
    });

    // Tender View Route
    Route::group(['middleware' => ['auth']], function () {
        Route::resource('tender', 'Common\TenderController', ['only' => 'show', 'name' => 'tender.show']);
    });
}


// -- Client Routes
Route::group(['prefix' => 'client', 'middleware' => ['auth', 'client']], function () {
    Route::get('/', function () {
        return redirect()->route('client.dashboard');
    });

    Route::get('dashboard', 'Client\DashboardController@index')->name('client.dashboard');
    Route::post('api/award', 'Common\TenderController@award')->name('tender.award');
    Route::post('api/change', 'Common\TenderController@change')->name('tender.change');
    Route::resource('tender', 'Common\TenderController', ['only' => [
        'create', 'store', 'edit', 'update'
     ], 'names' => [
        'create' => 'tender.create',
        'store'  => 'tender.store',
        'edit'   => 'tender.edit',
        'update'   => 'tender.update'
    ]]);
});


// -- Provider Routes
Route::group(['prefix' => 'provider', 'middleware' => ['auth', 'provider']], function () {
    Route::get('/', function () {
        return redirect()->route('provider.dashboard');
    });

    Route::get('dashboard', 'Provider\DashboardController@index')->name('provider.dashboard');
    Route::post('tender/{id}/bid', 'Provider\BidController@store')->name('tender.bid');
    Route::post('tender/{id}/bid/update', 'Provider\BidController@update')->name('tender.bid.update');
    Route::post('tender/{id}/bid/cancel', 'Provider\BidController@destroy')->name('tender.bid.cancel');
});


// -- Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('profile/switch/{id}', 'Common\UserController@switchBlock')->name('admin.switch');
    Route::get('dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
});


// -- Special Route: read and return the image
Route::get('public/profile_pics/{filename}', 'Common\ImageController@show');
