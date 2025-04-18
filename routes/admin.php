<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HasAccessAdmin;
use App\Http\Middleware\Admin\HandleInertiaAdminRequests;
use Inertia\Inertia;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\MemberController;

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth', HasAccessAdmin::class, HandleInertiaAdminRequests::class],
    'as' => 'admin.',
], function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('menu', 'MenuController')->except([
        'show',
    ]);
    Route::resource('menu.item', 'MenuItemController')->except([
        'show',
    ]);
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', 'CategoryTypeController')->except([
            'show',
        ]);
        Route::resource('type.item', 'CategoryController');
    });
    Route::resource('media', 'MediaController');


    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
    Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');
    Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::patch('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');


    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{branch}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{branch}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{branch}', [MemberController::class, 'update'])->name('members.update');
    Route::patch('/members/{branch}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{branch}', [MemberController::class, 'destroy'])->name('members.destroy');


    Route::get('edit-account-info', 'UserController@accountInfo')->name('account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('account.password.store');
});
