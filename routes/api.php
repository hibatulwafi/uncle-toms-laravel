<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthMember\AuthMemberController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CapsterController;
use App\Http\Controllers\MenuAppController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// API Mobile Apps
Route::prefix('app')->group(function () {
    // Authentication
    Route::post('/login', [AuthMemberController::class, 'loginMember'])->name('members.login');

    // Route::post('/email/verify/{id}/{hash}', [AuthMemberController::class, 'verifyEmail'])
    //     ->name('verification.verify')
    //     ->middleware(['signed', 'throttle:6,1']);

    // Route::post('/email/verification-notification', [AuthMemberController::class, 'sendVerificationEmail'])
    //     ->middleware(['auth:sanctum', 'auth:member', 'throttle:6,1']);

    // Route::post('/password/forgot', [AuthMemberController::class, 'forgotPassword']);
    // Route::post('/password/reset', [AuthMemberController::class, 'resetPassword'])->name('password.reset');

    Route::middleware(['auth:sanctum', 'auth:member'])->group(function () {
        Route::post('/logout', [AuthMemberController::class, 'logoutMember'])->name('members.logout');

        // Profile
        Route::get('/profile', [MemberController::class, 'profile'])->name('members.profile');
        Route::get('/profile/edit', [MemberController::class, 'editProfile'])->name('members.profile.edit');
        Route::patch('/profile', [MemberController::class, 'updateProfile'])->name('members.profile.update');
        Route::get('/change-password', [MemberController::class, 'changePassword'])->name('members.change-password');
        Route::patch('/change-password', [MemberController::class, 'updatePassword'])->name('members.password.update');

        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('members.ratings.index');
        Route::post('/ratings', [RatingController::class, 'store'])->name('members.ratings.store');

        // Branches
        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');

        // Services
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

        // Capsters
        Route::get('/capsters', [CapsterController::class, 'index'])->name('capsters.index');
        Route::get('/capsters/{capster}', [CapsterController::class, 'show'])->name('capsters.show');

        // Menus
        Route::get('/menus', [MenuAppController::class, 'index'])->name('menus.index');
        Route::get('/banners', [MenuAppController::class, 'index'])->name('banners.index');
        Route::get('/support', [MenuAppController::class, 'support'])->name('support.index');
        Route::get('/faqs', [MenuAppController::class, 'faqs'])->name('faqs.index');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

        // Scan QR
        Route::post('/scan', [ScanController::class, 'scan'])->name('scan');
        Route::post('/transaction', [ScanController::class, 'transaction'])->name('scan');
    });

    // Onboarding (biasanya tidak memerlukan otentikasi)
    Route::get('/onboarding', [AppController::class, 'onboarding'])->name('onboarding.index');
    Route::get('/latest', [AppController::class, 'getLatestAppVersion'])->name('app.latest_version');
});

# END API MOBILE APPS

# API WEBSITE
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
# END API WEBSITE