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
Route::get('/', function () {
    return redirect()->route('dashboard.home');
});
Route::get('/hashpw/{text}', function ($text) {
    return Hash::make($text);
});
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
            Route::prefix('export')->name('export.')->group(function () {
                Route::middleware(['permission:all_user_activity_full'])
                    ->get('userActivity', 'Dashboard\ReportController@exportAllUsersActivity')
                    ->name('allUsersActivity');

                Route::middleware(['permission:all_user_activity_full', 'signed'])
                    ->get('download/{filename}', 'Dashboard\ReportController@downloadReport')
                    ->name('download');
            });
        });

        Route::post('extractUserIds', 'Dashboard\ReportController@extractUserIds')->name('extractUserIds');

        Route::group(['middleware' => ['role:Admin']], function () {
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('user_list', 'Dashboard\AdminController@userList')->name('user_list');
                Route::get('user_item/{id?}', 'Dashboard\AdminController@userItem')->name('user_item');
                Route::post('user_item/{id?}', 'Dashboard\AdminController@userItemUpdate')->name('user_item_update');
                Route::get('resetPassword/{id}', 'Dashboard\AdminController@resetPasswordView')->name('resetPasswordView');
                Route::post('resetPassword/{id}', 'Dashboard\AdminController@resetPassword')->name('resetPassword');
            });
        });

        Route::group(['middleware' => ['permission:view_feedback']], function () {
            Route::get('feedbacks', 'Dashboard\ReportController@viewFeedback')->name('feedbacks');
            Route::get('comment/new/{id?}', 'Dashboard\ReportController@commentView')->name('commentView');
        });
        Route::middleware(['permission:response_feedback'])->group(function () {
            Route::post('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedback')->name('responseFeedback');
            Route::get('sendSms', 'Dashboard\ReportController@sendSms')->name('sendSms');
            Route::post('comment/new/{id?}', 'Dashboard\ReportController@addComment')->name('newComment');
            Route::get('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedbackView')->name('viewFeedback');
        });

        Route::group(['middleware' => ['permission:view_feedback']], function () {
            Route::get('notifications', 'Dashboard\ReportController@notifications')->name('notifications');
            Route::get('notification/new/{id?}', 'Dashboard\ReportController@notificationView')->name('notificationView');

            Route::get('announcements', 'Dashboard\FirebaseController@announcements')->name('announcements');
            Route::get('announcements/{id}', 'Dashboard\FirebaseController@announcementItem')->name('announcementItem');
            Route::post('announcements/{id}', 'Dashboard\FirebaseController@storeAnnouncement')->name('storeAnnouncement');
            Route::get('announcements/{id}/delete', 'Dashboard\FirebaseController@deleteAnnouncement')->name('deleteAnnouncement');

            Route::get('banners', 'Dashboard\ManagementController@banners')->name('banners');
            Route::get('banners/{id}', 'Dashboard\ManagementController@bannerItem')->name('bannerItem');
            Route::post('banners/{id}', 'Dashboard\ManagementController@storeBanner')->name('storeBanner');
        });

        Route::middleware(['permission:add_notification'])->post('notification/new/{id?}', 'Dashboard\ReportController@addNotification')->name('newNotification');
        Route::middleware(['permission:delete_notification'])->get('notification/delete/{id?}', 'Dashboard\ReportController@deleteNotification')->name('deleteNotification');


        Route::group(['middleware' => ['permission:view_promo_codes']], function () {
            Route::get('campaigns', 'Dashboard\ManagementController@campaigns')->name('campaigns');
            Route::get('campaigns/user', 'Dashboard\ManagementController@campaignUser')->name('campaignUser');
            Route::get('campaigns/{id}', 'Dashboard\ManagementController@campaignItem')->name('campaignItem');
            Route::post('campaigns/{id}', 'Dashboard\ManagementController@campaignStore')->name('campaignStore');
            Route::get('campaigns/{id}/delete', 'Dashboard\ManagementController@campaignDelete')->name('campaignDelete');

            Route::get('promoCodes', 'Dashboard\ManagementController@promoCodes')->name('promoCodes');
            Route::get('campaigns/{campaignId}/promoCodes/{id}', 'Dashboard\ManagementController@promoCodeItem')->name('promoCodeItem');
            Route::post('campaigns/{campaignId}/promoCodes/{id}', 'Dashboard\ManagementController@promoCodeStore')->name('promoCodeStore');
            Route::get('promoCodes/{id}/delete', 'Dashboard\ManagementController@promoCodeDelete')->name('promoCodeDelete');
        });

        Route::group(['middleware' => ['permission:view_transactions']], function () {
            Route::get('transactions', 'Dashboard\ManagementController@transactions')->name('transactions');
        });

        Route::get('generateReport', 'Dashboard\ManagementController@generateReport')->name('generateReport');

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
