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

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('login', 'Dashboard\AuthController@login')->name('login');
    Route::post('authenticate', 'Dashboard\AuthController@authenticate')->name('authenticate');

    Route::get('/', function () {
        if (auth()-> guest()) {
            return redirect(route('dashboard.login'));
        }
        return view('dashboard.home');
    })->name('home');

    Route::get('logout', function () {
        auth()->logout();
        return redirect(route('dashboard.login'));
    })->name('logout');

    Route::middleware('auth')->group(function () {
        /** User Activation */
        Route::get(
            'users/activation/step/{step}',
            'Dashboard\UserActivationController@activationIndex'
        )->name('users.activation');
        Route::get(
            'users/activation/{userId}/show',
            'Dashboard\UserActivationController@activationShow'
        )->name('users.activation.show');
        Route::put(
            'users/activation/{userId}/call',
            'Dashboard\UserActivationController@activationCall'
        )->name('users.activation.call.update');
    });
});

Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get(
        'users/activations/step/{step}',
        'Job\UserActivationController@UserActivationDispatcher'
    );
});
