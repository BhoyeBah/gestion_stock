<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Tenant\SubscriptionController as TenantSubscriptionController;


use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('back.layouts.admin');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
    Route::resource('/permissions', PermissionController::class)->middleware('subscription.permission:manage_permissions');
    Route::resource('/plans', PlanController::class)->middleware('subscription.permission:manage_plans');
    Route::resource('/tenants', TenantController::class)->middleware('subscription.permission:manage_tenants');
    Route::resource('/subscriptions', SubscriptionController::class)->middleware('subscription.permission:manage_subscriptions');
    Route::patch('/subscriptions/toggle/{subscription}', [SubscriptionController::class, "toggleActive"])->name('subscriptions.toggle');
});

Route::middleware(['auth', 'subscription.permission:manage_roles'])->resource('/roles', RoleController::class);
Route::middleware(['auth', 'subscription.permission:manage_users'])->resource('/users', UserController::class);
Route::patch('/users/{id}/toggle', [UserController::class, "toggle"])->middleware(['auth', 'subscription.permission:manage_users'])->name('users.toggle');


Route::middleware(['auth', 'subscription.permission:manage_invoices'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/subscriptions', [TenantSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [TenantSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::get('/subscriptions/{subscription}/pdf', [TenantSubscriptionController::class, 'pdf'])->name('subscriptions.pdf');
});

Route::resource('/activities', ActivityController::class)->middleware('auth')->names('user.activity');


Route::middleware(['auth', 'can:manage_notifications'])->prefix('admin')->group(function () {
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('admin.notifications.store');
});

Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',[ProfileController::class, 'edit'])->name('edit');
    Route::put('/',[ProfileController::class, 'update'])->name('update');
});


require __DIR__.'/auth.php';
