<?php

use App\Http\Controllers\AuthMember\AuthMemberController;
use App\Http\Controllers\AppMember\AppController;
use App\Http\Controllers\AppMember\BannerController;
use App\Http\Controllers\AppMember\BookingController;
use App\Http\Controllers\AppMember\BranchController;
use App\Http\Controllers\AppMember\CapsterController;
use App\Http\Controllers\AppMember\FaqController;
use App\Http\Controllers\AppMember\MemberController;
use App\Http\Controllers\AppMember\MenuAppController;
use App\Http\Controllers\AppMember\NotificationController;
use App\Http\Controllers\AppMember\OnboardingController;
use App\Http\Controllers\AppMember\RatingController;
use App\Http\Controllers\AppMember\ScanController;
use App\Http\Controllers\AppMember\ServiceController;
use App\Http\Controllers\AppMember\SupportController;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// API Mobile Apps
Route::prefix('app')->group(function () {
    // Authentication
    Route::post('/login', [AuthMemberController::class, 'loginMember'])->name('members.login');

    // Route::post('/email/verify/{id}/{hash}', [AuthMemberController::class, 'verifyEmail'])
    //     ->name('verification.verify')
    //     ->middleware(['signed', 'throttle:6,1'])->name('members.email.verify');

    // Route::post('/email/verification-notification', [AuthMemberController::class, 'sendVerificationEmail'])
    //     ->middleware(['auth:sanctum', 'auth:member', 'throttle:6,1'])->name('members.email.sendverification');

    // Route::post('/password/forgot', [AuthMemberController::class, 'forgotPassword'])->name('members.password.forgot');
    // Route::post('/password/reset', [AuthMemberController::class, 'resetPassword'])->name('members.password.reset');

    Route::middleware(['auth:sanctum', 'auth:member'])->group(function () {
        Route::post('/logout', [AuthMemberController::class, 'logoutMember'])->name('members.logout');

        // Profile
        Route::get('/profile', [MemberController::class, 'profile'])->name('members.profile.index');
        Route::get('/profile/edit', [MemberController::class, 'editProfile'])->name('members.profile.edit');
        Route::patch('/profile', [MemberController::class, 'updateProfile'])->name('members.profile.update');
        Route::get('/change-password', [MemberController::class, 'changePassword'])->name('members.change-password');
        Route::patch('/change-password', [MemberController::class, 'updatePassword'])->name('members.password.update');

        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('members.ratings.index');
        Route::post('/ratings', [RatingController::class, 'store'])->name('members.ratings.store');

        // Branches
        Route::get('/branches', [BranchController::class, 'index'])->name('members.branches.index');
        Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('members.branches.show');

        // Services
        Route::get('/services', [ServiceController::class, 'index'])->name('members.services.index');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('members.services.show');

        // Capsters
        Route::get('/capsters', [CapsterController::class, 'index'])->name('members.capsters.index');
        Route::get('/capsters/{capster}', [CapsterController::class, 'show'])->name('members.capsters.show');

        // Menus
        Route::get('/menus', [MenuAppController::class, 'index'])->name('members.menus.index');
        Route::get('/banners', [MenuAppController::class, 'index'])->name('members.banners.index');
        Route::get('/support', [MenuAppController::class, 'support'])->name('members.support.index');
        Route::get('/faqs', [MenuAppController::class, 'faqs'])->name('members.faqs.index');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('members.notifications.index');

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('members.bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('members.bookings.store');

        // Scan QR
        Route::get('/scan', [ScanController::class, 'previewScan'])->name('members.scan.preview');
        Route::post('/scan', [ScanController::class, 'scan'])->name('members.scan.store');
        Route::post('/transaction', [ScanController::class, 'transaction'])->name('members.scan.transaction');
    });

    // Onboarding (biasanya tidak memerlukan otentikasi)
    Route::get('/onboarding', [AppController::class, 'onboarding'])->name('members.onboarding.index');
    Route::get('/min-version', [AppController::class, 'getLatestAppVersion'])->name('members.app.latest_version');
});
# END API MOBILE APPS

# API WEBSITE
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
# END API WEBSITE