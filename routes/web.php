<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberRegistrationController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ContributionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LoanRepaymentController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\AmountHeldController;
use App\Http\Controllers\Admin\MemberSessionController;

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
    return view('welcome');
})->name('welcome');

Route::post('contact', [App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');

// User Authentication Routes
Route::prefix('user')->name('user.')->group(function () {
    // Public routes
    Route::get('login', [App\Http\Controllers\User\UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\User\UserAuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\User\UserAuthController::class, 'logout'])->name('logout');
    Route::get('password/reset', [App\Http\Controllers\User\UserAuthController::class, 'showResetPasswordForm'])->name('password.request');
    Route::post('password/reset', [App\Http\Controllers\User\UserAuthController::class, 'resetPassword'])->name('password.update');
    Route::get('password/generate', [App\Http\Controllers\User\UserAuthController::class, 'showGeneratePasswordForm'])->name('password.generate');
    Route::post('password/generate', [App\Http\Controllers\User\UserAuthController::class, 'generatePassword']);

    // Profile completion routes
    Route::get('profile/complete', [App\Http\Controllers\User\UserAuthController::class, 'showProfileComplete'])->name('profile.complete');
    Route::post('profile/complete', [App\Http\Controllers\User\UserAuthController::class, 'completeProfile'])->name('profile.store');
    Route::get('profile/pending', [App\Http\Controllers\User\UserAuthController::class, 'showPendingConfirmation'])->name('profile.pending');
    
    // Protected routes
    Route::middleware(['member.auth'])->group(function () {
        Route::get('dashboard', [App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');
        Route::post('profile/theme', [App\Http\Controllers\User\UserAuthController::class, 'updateTheme'])->name('profile.theme');
        Route::get('activity-log', [App\Http\Controllers\User\ActivityLogController::class, 'index'])->name('activity-log');

        // Member Reports route
        Route::get('report', [App\Http\Controllers\User\ReportController::class, 'index'])->name('report');
        
        // Loan routes
        Route::get('loans', [App\Http\Controllers\User\LoanController::class, 'index'])->name('loans.index');
        Route::get('loans/create', [App\Http\Controllers\User\LoanController::class, 'create'])->name('loans.create');
        Route::post('loans', [App\Http\Controllers\User\LoanController::class, 'store'])->name('loans.store');
        Route::get('loans/{loan}', [App\Http\Controllers\User\LoanController::class, 'show'])->name('loans.show');

        // Mortuary Aid contribution routes
        Route::get('mortuary-aid', [App\Http\Controllers\User\MortuaryContributionController::class, 'create'])->name('mortuary.create');
        Route::post('mortuary-aid', [App\Http\Controllers\User\MortuaryContributionController::class, 'store'])->name('mortuary.store');

        // Notification routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\User\NotificationController::class, 'index'])->name('index');
            Route::post('/{notification}/read', [App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('read-all');
            Route::delete('/{notification}', [App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('destroy');
        });

        // Receipt routes
        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('/', [App\Http\Controllers\User\ReceiptController::class, 'index'])->name('index');
            Route::get('/{repayment}', [App\Http\Controllers\User\ReceiptController::class, 'show'])->name('show');
            Route::get('/{repayment}/print', [App\Http\Controllers\User\ReceiptController::class, 'print'])->name('print');
        });
    });
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public admin routes
    Route::get('login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('account-settings', [App\Http\Controllers\Admin\AdminAccountSettingsController::class, 'index'])->name('account-settings.index');
        Route::put('account-settings', [App\Http\Controllers\Admin\AdminAccountSettingsController::class, 'update'])->name('account-settings.update');
    
        // Member Management
        Route::resource('members', MemberController::class);
        Route::post('members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');
        Route::post('members/{member}/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
        Route::get('member-sessions', [MemberSessionController::class, 'index'])->name('member-sessions.index');
        
        // Member Registration (Create accounts for members)
        Route::get('member-registration', [MemberRegistrationController::class, 'index'])->name('member-registration.index');
        Route::post('member-registration', [MemberRegistrationController::class, 'store'])->name('member-registration.store');
        Route::post('member-registration/confirm', [MemberRegistrationController::class, 'confirm'])->name('member-registration.confirm');
        Route::get('member-password', [App\Http\Controllers\Admin\MemberPasswordController::class, 'index'])->name('member-password.index');
        Route::post('member-password', [App\Http\Controllers\Admin\MemberPasswordController::class, 'generate'])->name('member-password.generate');
        
        // Finance Management (Combined Loans & Contributions)
        Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::get('finance/repayment-confirmation', [FinanceController::class, 'repaymentConfirmation'])->name('finance.repayment-confirmation');
        Route::post('finance/batch-confirm-repayments', [FinanceController::class, 'batchConfirmRepayments'])->name('finance.batch-confirm-repayments');
        Route::post('finance/loans/{loan}/mark-didnt-pay', [FinanceController::class, 'markDidntPay'])->name('finance.mark-didnt-pay');
        Route::post('finance/receipt/issue', [App\Http\Controllers\Admin\ReceiptController::class, 'issue'])->name('finance.receipt.issue');

        // Receipt Management
        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReceiptController::class, 'index'])->name('index');
            Route::get('/{receipt}', [App\Http\Controllers\Admin\ReceiptController::class, 'show'])->name('show');
            Route::get('/{receipt}/print', [App\Http\Controllers\Admin\ReceiptController::class, 'print'])->name('print');
            Route::post('/{receipt}/confirm', [App\Http\Controllers\Admin\ReceiptController::class, 'confirm'])->name('confirm');
        });
        
        // Amount Held Management
        Route::get('amount-held', [AmountHeldController::class, 'index'])->name('amount-held.index');
        Route::post('amount-held/cash', [AmountHeldController::class, 'updateCash'])->name('amount-held.cash.update');
        Route::post('amount-held/bank', [AmountHeldController::class, 'storeBank'])->name('amount-held.bank.store');
        Route::put('amount-held/bank/{fund}', [AmountHeldController::class, 'updateBank'])->name('amount-held.bank.update');
        Route::delete('amount-held/bank/{fund}', [AmountHeldController::class, 'destroyBank'])->name('amount-held.bank.destroy');
        
        // Redirect old index routes to unified Finance page
        Route::get('loans', fn() => redirect()->route('admin.finance.index', ['tab' => 'loans']))->name('loans.index');
        Route::get('contributions', fn() => redirect()->route('admin.finance.index', ['tab' => 'contributions']))->name('contributions.index');
        
        // Loan Management (excluding index - handled by finance)
        Route::resource('loans', LoanController::class)->except(['index']);
        Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('loans/{loan}/repayments', [LoanRepaymentController::class, 'store'])->name('loans.repayments.store');
        Route::get('members/{member}/data', [LoanController::class, 'getMemberData'])->name('members.data');
          
        // Contribution Management (excluding index - handled by finance)
        Route::resource('contributions', ContributionController::class)->except(['index']);
        Route::post('contributions/{contribution}/approve', [ContributionController::class, 'approve'])->name('contributions.approve');
        Route::post('contributions/{contribution}/reject', [ContributionController::class, 'reject'])->name('contributions.reject');
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/contributions', [ReportController::class, 'contributions'])->name('reports.contributions');
        Route::get('reports/loans', [ReportController::class, 'loans'])->name('reports.loans');
        Route::get('reports/dividends', [ReportController::class, 'dividends'])->name('reports.dividends');
        Route::post('reports/dividends/calculate', [ReportController::class, 'calculateDividends'])->name('reports.dividends.calculate');
        Route::post('reports/dividends/release', [ReportController::class, 'releaseDividends'])->name('reports.dividends.release');
        Route::get('reports/schedule', [ReportController::class, 'schedule'])->name('reports.schedule');
        Route::get('reports/schedule/cbu', [ReportController::class, 'scheduleCbu'])->name('reports.schedule.cbu');
        Route::get('reports/schedule/loans-receivable', [ReportController::class, 'scheduleLoansReceivable'])->name('reports.schedule.loans-receivable');
        Route::get('reports/schedule/savings', [ReportController::class, 'scheduleSavings'])->name('reports.schedule.savings');
        Route::get('reports/schedule/ssfd', [ReportController::class, 'scheduleSsfd'])->name('reports.schedule.ssfd');
        Route::get('reports/schedule/mortuary-aid', [ReportController::class, 'scheduleMortuaryAid'])->name('reports.schedule.mortuary-aid');
        Route::get('reports/schedule/monthly-mortuary-aid', [ReportController::class, 'scheduleMonthlyMortuaryAid'])->name('reports.schedule.monthly-mortuary-aid');
        Route::get('reports/schedule/monthly-cbu', [ReportController::class, 'scheduleMonthlyCbu'])->name('reports.schedule.monthly-cbu');
        Route::get('reports/schedule/interest-contribution', [ReportController::class, 'scheduleInterestContribution'])->name('reports.schedule.interest-contribution');
        Route::get('reports/schedule/monthly-contribution', [ReportController::class, 'scheduleMonthlyContribution'])->name('reports.schedule.monthly-contribution');
        Route::get('reports/schedule/contributions', [ReportController::class, 'scheduleContributions'])->name('reports.schedule.contributions');
        Route::get('reports/activity-logs', [ReportController::class, 'activityLogs'])->name('reports.activity-logs');
        Route::get('reports/contributions/export', [ReportController::class, 'exportContributions'])->name('reports.contributions.export');
        Route::get('reports/loans/export', [ReportController::class, 'exportLoans'])->name('reports.loans.export');
        Route::get('reports/dividends/export', [ReportController::class, 'exportDividends'])->name('reports.dividends.export');
        Route::get('reports/schedule/export', [ReportController::class, 'exportSchedule'])->name('reports.schedule.export');
        Route::get('reports/schedule/cbu/export', [ReportController::class, 'exportScheduleCbu'])->name('reports.schedule.cbu.export');
        Route::get('reports/schedule/loans-receivable/export', [ReportController::class, 'exportScheduleLoansReceivable'])->name('reports.schedule.loans-receivable.export');
        Route::get('reports/schedule/savings/export', [ReportController::class, 'exportScheduleSavings'])->name('reports.schedule.savings.export');
        Route::get('reports/schedule/ssfd/export', [ReportController::class, 'exportScheduleSsfd'])->name('reports.schedule.ssfd.export');
        Route::get('reports/schedule/mortuary-aid/export', [ReportController::class, 'exportScheduleMortuaryAid'])->name('reports.schedule.mortuary-aid.export');
        Route::get('reports/schedule/monthly-mortuary-aid/export', [ReportController::class, 'exportScheduleMonthlyMortuaryAid'])->name('reports.schedule.monthly-mortuary-aid.export');
        Route::get('reports/schedule/monthly-cbu/export', [ReportController::class, 'exportScheduleMonthlyCbu'])->name('reports.schedule.monthly-cbu.export');
        Route::get('reports/schedule/interest-contribution/export', [ReportController::class, 'exportScheduleInterestContribution'])->name('reports.schedule.interest-contribution.export');
        Route::get('reports/schedule/monthly-contribution/export', [ReportController::class, 'exportScheduleMonthlyContribution'])->name('reports.schedule.monthly-contribution.export');
        Route::get('reports/schedule/contributions/export', [ReportController::class, 'exportScheduleContributions'])->name('reports.schedule.contributions.export');
        Route::get('reports/activity-logs/export', [ReportController::class, 'exportActivityLogs'])->name('reports.activity-logs.export');

        // Contact Messages (from website)
        Route::get('messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{message}/read', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('messages.read');
        Route::delete('messages/{message}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');

        // Admin Notification routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/announcement', [NotificationController::class, 'storeAnnouncement'])->name('announcement.store');
            Route::delete('/announcement/{announcement}', [NotificationController::class, 'destroyAnnouncement'])->name('announcement.destroy');
            Route::post('/loan/{loan}/send-demand', [NotificationController::class, 'sendPaymentDemand'])->name('loan.send-demand');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        });
    });
});