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

    Route::get('/home', function () {
        if (auth()->guest()) {
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
        Route::prefix('report')->name('report.')->group(function () {
            Route::middleware(['permission:time_separation'])
                ->match(['get', 'post'], 'timeSeparation', 'Dashboard\ReportController@timeSeparation')
                ->name('timeSeparation');
            Route::middleware(['permission:day_separation'])
                ->match(['get', 'post'], 'daySeparation', 'Dashboard\ReportController@daySeparation')
                ->name('daySeparation');
            Route::middleware(['permission:range_separation'])
                ->match(['get', 'post'], 'rangeSeparation', 'Dashboard\ReportController@rangeSeparation')
                ->name('rangeSeparation');
            Route::middleware(['permission:all_user_activity_full|all_user_activity'])
                ->match(['get', 'post'], 'userActivity', 'Dashboard\ReportController@allUsersActivity')
                ->name('allUsersActivity');
            Route::middleware(['permission:all_project_activity'])
                ->match(['get', 'post'], 'projectActivity', 'Dashboard\ReportController@allProjectsActivity')
                ->name('allProjectsActivity');
            Route::middleware(['permission:user_activity'])
                ->match(['get', 'post'], 'userActivity/{id}', 'Dashboard\ReportController@userActivity')
                ->name('userActivity');
            Route::middleware(['permission:project_activity'])
                ->match(['get', 'post'], 'projectActivity/{id}', 'Dashboard\ReportController@projectActivity')
                ->name('projectActivity');
        });

        Route::group(['middleware' => ['permission:view_feedback']], function () {
            Route::get( 'feedbacks', 'Dashboard\ReportController@viewFeedback')->name('feedbacks');
            Route::get('comment/new/{id?}', 'Dashboard\ReportController@commentView')->name('commentView');
            Route::post('comment/new/{id?}', 'Dashboard\ReportController@addComment')->name('newComment');
            Route::get('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedbackView')->name('viewFeedback');
        });
        Route::middleware(['permission:response_feedback'])->group(function () {
            Route::post('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedback')->name('responseFeedback');
        });

        Route::get('changePassword', 'Dashboard\ReportController@changePasswordView')->name('changePasswordView');
        Route::post('changePassword', 'Dashboard\ReportController@changePassword')->name('changePassword');
    });
});

Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get(
        'users/activations/step/{step}',
        'Job\UserActivationController@UserActivationDispatcher'
    );
});
