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

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\AutomationController;
use App\Http\Controllers\Dashboard\FileController;
use App\Http\Controllers\Dashboard\FirebaseController;
use App\Http\Controllers\Dashboard\ManagementController;
use App\Http\Controllers\Dashboard\PremiumController;
use App\Http\Controllers\Dashboard\ReportController;

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
    Route::get('reload-captcha', function () {
        return response()->json(['captcha' => captcha_img()]);
    });
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('test', [AutomationController::class, 'getRegistrationAutomation']);
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
                Route::get('wallet', [PremiumController::class, 'walletView'])->name('wallet');
                Route::post('wallet', [PremiumController::class, 'walletStore']);

                Route::get('purchase/{type}/{id}', [PremiumController::class, 'purchase'])->name('purchase');
                Route::post('purchase/{type}/{id}', [PremiumController::class, 'previewPurchase'])->name('previewPurchase');

                Route::get('invoices/{id}/delete', [PremiumController::class, 'deleteInvoice'])->name('deleteInvoice');
                Route::post('invoices/{id}/pay', [PremiumController::class, 'payInvoice'])->name('payInvoice');

                Route::post('plan/{id}/close', [PremiumController::class, 'closePlan'])->name('closePlan');
            });

        });

        Route::prefix('logCenters')->name('log_centers.')->middleware(['permission:view_log_center'])->group(function () {
            Route::get('/', [ManagementController::class, 'logCenters'])->name('index');
            Route::get('/{id}', [ManagementController::class, 'logCenterItem'])->name('show');
        });

        Route::get('files', [FileController::class, 'files'])->name('downloadCenter');
        Route::get('download/{id}', [FileController::class, 'downloadFile'])
            ->name('downloadFile');
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('premiumReport', [ManagementController::class, 'premiumReport'])->name('premiumReport');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'timeSeparation', [ReportController::class, 'timeSeparation'])
                ->name('timeSeparation');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'daySeparation', [ReportController::class, 'daySeparation'])
                ->name('daySeparation');
            Route::middleware(['permission:view_registration'])
                ->match(['get', 'post'], 'rangeSeparation', [ReportController::class, 'rangeSeparation'])
                ->name('rangeSeparation');
            Route::middleware(['permission:view_users_report'])->group(function () {
                Route::match(['get', 'post'], 'userActivity', [ReportController::class, 'allUsersActivity'])
                    ->name('allUsersActivity');
                Route::get('extractUserIdsWithFilter', [ReportController::class, 'extractUserIdsWithFilter'])
                    ->name('extractUserIdsWithFilter');
                Route::get('userActivityCountChart', [ReportController::class, 'allUsersCountChart'])
                    ->name('userActivityCountChart');
                Route::get('userActivityRangeChart', [ReportController::class, 'allUsersRangeChart'])
                    ->name('userActivityRangeChart');
            });
            Route::middleware(['permission:view_projects_report'])
                ->match(['get', 'post'], 'projectActivity', [ReportController::class, 'allProjectsActivity'])
                ->name('allProjectsActivity');
            Route::middleware(['permission:view_users_report'])
                ->match(['get', 'post'], 'userActivity/{id}', [ReportController::class, 'userActivity'])
                ->name('userActivity');
            Route::middleware(['permission:view_projects_report'])
                ->match(['get', 'post'], 'projectActivity/{id}', [ReportController::class, 'projectActivity'])
                ->name('projectActivity');
            Route::prefix('export')->name('export.')->group(function () {
                Route::middleware(['permission:export_users_report'])
                    ->get('userActivity', [ReportController::class, 'exportAllUsersActivity'])
                    ->name('allUsersActivity');

                Route::middleware(['permission:export_projects_report'])
                    ->get('projectActivity', [ReportController::class, 'exportAllProjectsActivity'])
                    ->name('allProjectsActivity');
            });

            Route::middleware(['permission:view_extend_user_report'])
                ->get('userExtendReport', [ReportController::class, 'userExtendReport'])
                ->name('userExtendReport');

            Route::middleware(['permission:view_unverified_user'])
                ->get('unverifiedPaymentReport', [ReportController::class, 'unverifiedPaymentReport'])
                ->name('unverifiedPaymentReport');
        });

        Route::post('extractUserIds', [ReportController::class, 'extractUserIds'])->name('extractUserIds');

        Route::group(['middleware' => ['can:edit_user_panels']], function () {
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('user_list', [AdminController::class, 'userList'])->name('user_list');
                Route::get('user_item/{id?}', [AdminController::class, 'userItem'])->name('user_item');
                Route::post('user_item/{id?}', [AdminController::class, 'userItemUpdate'])->name('user_item_update');
                Route::get('resetPassword/{id}', [AdminController::class, 'resetPasswordView'])->name('resetPasswordView');
                Route::post('resetPassword/{id}', [AdminController::class, 'resetPassword'])->name('resetPassword');
            });
        });

        Route::group(['middleware' => ['permission:view_feedback']], function () {
            Route::get('feedbacks', [ReportController::class, 'viewFeedback'])->name('feedbacks');
            Route::get('comment/new/{id?}', [ReportController::class, 'commentView'])->name('commentView');
            Route::get('feedback/{feedback_id}/response', [ReportController::class, 'responseFeedbackView'])->name('viewFeedback');
            Route::group(['middleware' => ['permission:new_feedback']], function () {
                Route::post('comment/new/{id?}', [ReportController::class, 'addComment'])->name('newComment');
            });
        });
        Route::middleware(['permission:response_feedback'])->group(function () {
            Route::group(['middleware' => ['permission:response_feedback']], function () {
                Route::post('feedback/{feedback_id}/response', [ReportController::class, 'responseFeedback'])->name('responseFeedback');
            });
            Route::get('sendSms', [ReportController::class, 'sendSms'])->name('sendSms');
        });

        Route::group(['middleware' => ['permission:view_notification']], function () {
            Route::get('announcements', [FirebaseController::class, 'announcements'])->name('announcements');
            Route::get('announcements/{id}', [FirebaseController::class, 'announcementItem'])->name('announcementItem');
            Route::group(['middleware' => ['permission:new_notification']], function () {
                Route::post('announcements/{id}', [FirebaseController::class, 'storeAnnouncement'])->name('storeAnnouncement');
                Route::get('announcements/{id}/delete', [FirebaseController::class, 'deleteAnnouncement'])->name('deleteAnnouncement');
            });
        });

        Route::group(['middleware' => ['permission:view_banner']], function () {
            Route::get('banners', [ManagementController::class, 'banners'])->name('banners');
            Route::get('banners/{id}', [ManagementController::class, 'bannerItem'])->name('bannerItem');
            Route::group(['middleware' => ['permission:edit_banner']], function () {
                Route::post('banners/{id}', [ManagementController::class, 'storeBanner'])->name('storeBanner');
                Route::get('banners/{id}/delete', [ManagementController::class, 'deleteBanner'])->name('deleteBanner');
            });
        });

        Route::group(['middleware' => ['permission:view_promo_code']], function () {
            Route::get('campaigns', [ManagementController::class, 'campaigns'])->name('campaigns');
            Route::get('campaigns/user', [ManagementController::class, 'campaignUser'])->name('campaignUser');
            Route::get('campaigns/{id}', [ManagementController::class, 'campaignItem'])->name('campaignItem');
            Route::group(['middleware' => ['permission:edit_promo_code']], function () {
                Route::post('campaigns/{id}', [ManagementController::class, 'campaignStore'])->name('campaignStore');
            });
            Route::get('campaigns/{id}/delete', [ManagementController::class, 'campaignDelete'])->name('campaignDelete');

            Route::get('promoCodes', [ManagementController::class, 'promoCodes'])->name('promoCodes');
            Route::get('campaigns/{campaignId}/promoCodes/{id}', [ManagementController::class, 'promoCodeItem'])->name('promoCodeItem');
            Route::group(['middleware' => ['permission:edit_promo_code']], function () {
                Route::post('campaigns/{campaignId}/promoCodes/{id}', [ManagementController::class, 'promoCodeStore'])->name('promoCodeStore');
                Route::get('promoCodes/{id}/delete', [ManagementController::class, 'promoCodeDelete'])->name('promoCodeDelete');
            });
        });

        Route::prefix('automation')->name('automation.')->middleware('permission:view_automation')->group(function () {
            Route::get('metrics', [AutomationController::class, 'metrics'])->name('metrics');
            Route::get('exportMetrics', [AutomationController::class, 'exportMetrics'])
                ->name('export_metrics')->middleware(['permission:export_automation_metrics']);
            Route::get('types', [AutomationController::class, 'typeList'])->name('types');
            Route::get('typeItem/{id}', [AutomationController::class, 'typeItem'])->name('typeItem');
            Route::get('callLogs/{id}', [AutomationController::class, 'callLogs'])->name('callLogs');
            Route::post('burnUser/{id}', [AutomationController::class, 'burnUser'])->name('burnUser');
            Route::get('call/{userId}/{id}', [AutomationController::class, 'newCallView'])->name('callView');
            Route::post('newCall/{userId}/{id}', [AutomationController::class, 'newCall'])->name('newCall');
            Route::get('missCall/{userId}', [AutomationController::class, 'missCall'])->name('missCall');
        });

        Route::group(['middleware' => ['permission:view_transactions']], function () {
            Route::get('transactions', [ManagementController::class, 'transactions'])->name('transactions');
        });

        Route::group(['middleware' => ['permission:refresh_users_report|refresh_projects_report']], function () {
            Route::get('generateReport', [ManagementController::class, 'generateReport'])->name('generateReport');
        });

        Route::get('changePassword', [ReportController::class, 'changePasswordView'])->name('changePasswordView');
        Route::post('changePassword', [ReportController::class, 'changePassword'])->name('changePassword');
    });
});
