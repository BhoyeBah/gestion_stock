<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UnitsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Tenant\SubscriptionController as TenantSubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('/permissions', PermissionController::class)->middleware('subscription.permission:manage_permissions');
    Route::resource('/plans', PlanController::class)->middleware('subscription.permission:manage_plans');
    Route::resource('/tenants', TenantController::class)->middleware('subscription.permission:manage_tenants');
    Route::resource('/subscriptions', SubscriptionController::class)->middleware('subscription.permission:manage_subscriptions');
    Route::patch('/subscriptions/toggle/{subscription}', [SubscriptionController::class, 'toggleActive'])->name('subscriptions.toggle');
});

Route::middleware(['auth', 'subscription.permission:manage_roles'])->resource('/roles', RoleController::class);
Route::middleware(['auth', 'subscription.permission:manage_users'])->resource('/users', UserController::class);
Route::patch('/users/{id}/toggle', [UserController::class, 'toggle'])->middleware(['auth', 'subscription.permission:manage_users'])->name('users.toggle');

Route::middleware(['auth', 'subscription.permission:manage_invoices'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/subscriptions', [TenantSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [TenantSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::get('/subscriptions/{subscription}/pdf', [TenantSubscriptionController::class, 'pdf'])->name('subscriptions.pdf');
});

Route::resource('/activities', ActivityController::class)->middleware('auth')->names('user.activity');
Route::resource('/units', UnitsController::class)->names('admin.units');
Route::resource('/categories', CategoryController::class)->middleware(['auth', 'subscription.permission:manage_categories'])->names('categories');

Route::patch('/products/{id}', [ProductController::class, 'toggleActive'])->name('products.toggle');
Route::resource('/products', ProductController::class)->middleware(['auth', 'subscription.permission:read_products'])->names('products');

Route::patch('/warehouses/{id}', [WarehouseController::class, 'toggleActive'])->name('warehouses.toggle');
Route::resource('/warehouses', WarehouseController::class)->middleware(['auth', 'subscription.permission:manage_warehouses'])->names('warehouses');

Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');

Route::middleware(['auth', 'can:manage_notifications'])->prefix('admin')->group(function () {
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('admin.notifications.store');
});

Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
});

Route::prefix('clients')->controller(ContactController::class)->name('clients.')->group(function () {
    Route::get('/', 'index')->name('index')->defaults('type', 'clients');
    Route::get('/create', 'create')->name('create')->defaults('type', 'clients');
    Route::post('/', 'store')->name('store')->defaults('type', 'clients');
    Route::get('/{contact}', 'show')->name('show')->defaults('type', 'clients');
    Route::get('/{contact}/edit', 'edit')->name('edit')->defaults('type', 'clients');
    Route::put('/{contact}', 'update')->name('update')->defaults('type', 'clients');
    Route::delete('/{contact}', 'destroy')->name('destroy')->defaults('type', 'clients');
    Route::patch('/{id}', 'toggleActive')->name('toggle')->defaults('type', 'clients');

});

Route::prefix('suppliers')->controller(ContactController::class)->name('suppliers.')->group(function () {
    Route::get('/', 'index')->name('index')->defaults('type', 'suppliers');
    Route::get('/create', 'create')->name('create')->defaults('type', 'suppliers');
    Route::post('/', 'store')->name('store')->defaults('type', 'suppliers');
    Route::get('/{contact}', 'show')->name('show')->defaults('type', 'suppliers');
    Route::get('/{contact}/edit', 'edit')->name('edit')->defaults('type', 'suppliers');
    Route::put('/{contact}', 'update')->name('update')->defaults('type', 'suppliers');
    Route::delete('/{contact}', 'destroy')->name('destroy')->defaults('type', 'suppliers');
    Route::patch('/{id}', 'toggleActive')->name('toggle')->defaults('type', 'suppliers');

});

Route::prefix('invoices/{type}')->controller(InvoiceController::class)->name('invoices.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/{invoice}/edit', 'edit')->name('edit');
    Route::put('/{invoice}', 'update')->name('update');
    Route::delete('/{invoice}', 'destroy')->name('destroy');
    Route::get('/{invoice}', 'show')->where('invoice', '[0-9a-fA-F\-]{36}')->name('show');
    Route::patch('/{invoice}/validate', 'validateInvoice')->where('invoice', '[0-9a-fA-F\-]{36}')->name('validate');
    Route::patch('/{invoice}/pay', 'validatePay')->where('invoice', '[0-9a-fA-F\-]{36}')->name('pay');
})->where('type', 'client|supplier');


// Route::resource('/payments', PaymentController::class)->middleware(['auth'])->names('payments');
Route::prefix('payments/{type}')->controller(PaymentController::class)->name("payments.")->group(function () {
    Route::get('/', 'index')->name('index');
    Route::delete('/{payment}', 'destroy')->name('destroy');
    Route::get('/{payment}', 'show')->where('payment', '[0-9a-fA-F\-]{36}')->name('show');
})->where('type', 'client|supplier');

Route::resource("expenses", ExpenseController::class)->middleware(['auth'])->names('expenses');

require __DIR__.'/auth.php';
