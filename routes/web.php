<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SubscriptionController;

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
    return view('back.layouts.admin');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('plans', PlanController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('tenants', TenantController::class);

    Route::resource('subscriptions', SubscriptionController::class);
    Route::patch('admin/subscriptions/{subscription}/toggle', [SubscriptionController::class, 'toggleActive'])
    ->name('subscriptions.toggle');

});
