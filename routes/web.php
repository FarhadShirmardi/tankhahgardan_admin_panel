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
Route::get('_ping', function () {
    return 'ok';
});
Route::get('/hashpw/{text}', function ($text) {
    return Hash::make($text);
});
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('login', 'Dashboard\AuthController@login')->name('login');
    Route::post('authenticate', 'Dashboard\AuthController@authenticate')->name('authenticate');
    Route::get('test', 'Dashboard\AutomationController@getRegistrationAutomation');
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
        Route::prefix('premium')->name('premium.')->middleware(['permission:edit_premium'])->group(function () {
            Route::prefix('{user_id}')->group(function () {
                Route::get('wallet', 'Dashboard\PremiumController@walletView')->name('wallet');
                Route::post('wallet', 'Dashboard\PremiumController@walletStore');

                Route::get('purchase/{type}/{id}', 'Dashboard\PremiumController@purchase')->name('purchase');
                Route::post('purchase/{type}/{id}', 'Dashboard\PremiumController@previewPurchase')->name('previewPurchase');
                Route::get('invoices/{id}/delete', 'Dashboard\PremiumController@deleteInvoice')->name('deleteInvoice');
                Route::post('invoices/{id}/pay', 'Dashboard\PremiumController@payInvoice')->name('payInvoice');

                Route::post('plan/{id}/close', 'Dashboard\PremiumController@closePlan')->name('closePlan');
            });

        });

        Route::prefix('logCenters')->name('log_centers.')->middleware(['permission:view_log_center'])->group(function () {
            Route::get('/', 'Dashboard\ManagementController@logCenters')->name('index');
            Route::get('/{id}', 'Dashboard\ManagementController@logCenterItem')->name('show');
        });

        Route::get('files', 'Dashboard\FileController@files')->name('downloadCenter');
        Route::get('download/{id}', 'Dashboard\FileController@downloadFile')
            ->name('downloadFile');
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('premiumReport', 'Dashboard\ManagementController@premiumReport')->name('premiumReport');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'timeSeparation', 'Dashboard\ReportController@timeSeparation')
                ->name('timeSeparation');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'daySeparation', 'Dashboard\ReportController@daySeparation')
                ->name('daySeparation');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'rangeSeparation', 'Dashboard\ReportController@rangeSeparation')
                ->name('rangeSeparation');
            Route::middleware(['permission:view_users_report'])->group(function () {
                Route::match(['get', 'post'], 'userActivity', 'Dashboard\ReportController@allUsersActivity')
                    ->name('allUsersActivity');
                Route::get('extractUserIdsWithFilter', 'Dashboard\ReportController@extractUserIdsWithFilter')
                    ->name('extractUserIdsWithFilter');
                Route::get('userActivityCountChart', 'Dashboard\ReportController@allUsersCountChart')
                    ->name('userActivityCountChart');
                Route::get('userActivityRangeChart', 'Dashboard\ReportController@allUsersRangeChart')
                    ->name('userActivityRangeChart');
            });
            Route::middleware(['permission:view_projects_report'])
                ->match(['get', 'post'], 'projectActivity', 'Dashboard\ReportController@allProjectsActivity')
                ->name('allProjectsActivity');
            Route::middleware(['permission:view_users_report'])
                ->match(['get', 'post'], 'userActivity/{id}', 'Dashboard\ReportController@userActivity')
                ->name('userActivity');
            Route::middleware(['permission:view_projects_report'])
                ->match(['get', 'post'], 'projectActivity/{id}', 'Dashboard\ReportController@projectActivity')
                ->name('projectActivity');
            Route::prefix('export')->name('export.')->group(function () {
                Route::middleware(['permission:export_users_report'])
                    ->get('userActivity', 'Dashboard\ReportController@exportAllUsersActivity')
                    ->name('allUsersActivity');

                Route::middleware(['permission:export_projects_report'])
                    ->get('projectActivity', 'Dashboard\ReportController@exportAllProjectsActivity')
                    ->name('allProjectsActivity');
            });
        });

        Route::post('extractUserIds', 'Dashboard\ReportController@extractUserIds')->name('extractUserIds');

        Route::group(['middleware' => ['can:edit_user_panels']], function () {
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
            Route::get('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedbackView')->name('viewFeedback');
        });
        Route::middleware(['permission:response_feedback'])->group(function () {
            Route::group(['middleware' => ['permission:response_feedback']], function () {
                Route::post('feedback/{feedback_id}/response', 'Dashboard\ReportController@responseFeedback')->name('responseFeedback');
            });
            Route::get('sendSms', 'Dashboard\ReportController@sendSms')->name('sendSms');
            Route::group(['middleware' => ['permission:new_feedback']], function () {
                Route::post('comment/new/{id?}', 'Dashboard\ReportController@addComment')->name('newComment');
            });
        });

        Route::group(['middleware' => ['permission:view_feedback']], function () {
            Route::get('notifications', 'Dashboard\ReportController@notifications')->name('notifications');
            Route::get('notification/new/{id?}', 'Dashboard\ReportController@notificationView')->name('notificationView');
        });

        Route::group(['middleware' => ['permission:view_notification']], function () {
            Route::get('announcements', 'Dashboard\FirebaseController@announcements')->name('announcements');
            Route::get('announcements/{id}', 'Dashboard\FirebaseController@announcementItem')->name('announcementItem');
            Route::group(['middleware' => ['permission:new_notification']], function () {
                Route::post('announcements/{id}', 'Dashboard\FirebaseController@storeAnnouncement')->name('storeAnnouncement');
                Route::get('announcements/{id}/delete', 'Dashboard\FirebaseController@deleteAnnouncement')->name('deleteAnnouncement');
            });
        });

        Route::group(['middleware' => ['permission:view_banner']], function () {
            Route::get('banners', 'Dashboard\ManagementController@banners')->name('banners');
            Route::get('banners/{id}', 'Dashboard\ManagementController@bannerItem')->name('bannerItem');
            Route::group(['middleware' => ['permission:edit_banner']], function () {
                Route::post('banners/{id}', 'Dashboard\ManagementController@storeBanner')->name('storeBanner');
                Route::get('banners/{id}/delete', 'Dashboard\ManagementController@deleteBanner')->name('deleteBanner');
            });
        });

        Route::group(['middleware' => ['permission:view_promo_code']], function () {
            Route::get('campaigns', 'Dashboard\ManagementController@campaigns')->name('campaigns');
            Route::get('campaigns/user', 'Dashboard\ManagementController@campaignUser')->name('campaignUser');
            Route::get('campaigns/{id}', 'Dashboard\ManagementController@campaignItem')->name('campaignItem');
            Route::group(['middleware' => ['permission:edit_promo_code']], function () {
                Route::post('campaigns/{id}', 'Dashboard\ManagementController@campaignStore')->name('campaignStore');
            });
            Route::get('campaigns/{id}/delete', 'Dashboard\ManagementController@campaignDelete')->name('campaignDelete');

            Route::get('promoCodes', 'Dashboard\ManagementController@promoCodes')->name('promoCodes');
            Route::get('campaigns/{campaignId}/promoCodes/{id}', 'Dashboard\ManagementController@promoCodeItem')->name('promoCodeItem');
            Route::group(['middleware' => ['permission:edit_promo_code']], function () {
                Route::post('campaigns/{campaignId}/promoCodes/{id}', 'Dashboard\ManagementController@promoCodeStore')->name('promoCodeStore');
                Route::get('promoCodes/{id}/delete', 'Dashboard\ManagementController@promoCodeDelete')->name('promoCodeDelete');
            });
        });

        Route::prefix('automation')->name('automation.')->middleware('permission:view_automation')->group(function () {
            Route::get('metrics', 'Dashboard\AutomationController@metrics')->name('metrics');
            Route::get('types', 'Dashboard\AutomationController@typeList')->name('types');
            Route::get('typeItem/{id}', 'Dashboard\AutomationController@typeItem')->name('typeItem');
            Route::get('callLogs/{id}', 'Dashboard\AutomationController@callLogs')->name('callLogs');
            Route::post('burnUser/{id}', 'Dashboard\AutomationController@burnUser')->name('burnUser');
            Route::get('call/{userId}/{id}', 'Dashboard\AutomationController@newCallView')->name('callView');
            Route::post('newCall/{userId}/{id}', 'Dashboard\AutomationController@newCall')->name('newCall');
        });

        Route::group(['middleware' => ['permission:view_transactions']], function () {
            Route::get('transactions', 'Dashboard\ManagementController@transactions')->name('transactions');
        });

        Route::group(['middleware' => ['permission:refresh_users_report|refresh_projects_report']], function () {
            Route::get('generateReport', 'Dashboard\ManagementController@generateReport')->name('generateReport');
        });

        Route::get('changePassword', 'Dashboard\ReportController@changePasswordView')->name('changePasswordView');
        Route::post('changePassword', 'Dashboard\ReportController@changePassword')->name('changePassword');
    });
});
