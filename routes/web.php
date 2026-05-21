<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{id}/archive', [\App\Http\Controllers\Admin\UserController::class, 'archive'])->name('users.archive');
    Route::patch('users/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
    Route::get('users-archived', [\App\Http\Controllers\Admin\UserController::class, 'archived'])->name('users.archived');
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
    Route::get('employees-archived', [\App\Http\Controllers\Admin\EmployeeController::class, 'archived'])->name('employees.archived');
    Route::patch('employees/{employee}/restore', [\App\Http\Controllers\Admin\EmployeeController::class, 'restore'])->name('employees.restore');
    Route::resource('destinations', \App\Http\Controllers\Admin\DestinationController::class);
    Route::get('destinations-archived', [\App\Http\Controllers\Admin\DestinationController::class, 'archived'])->name('destinations.archived');
    Route::patch('destinations/{destination}/restore', [\App\Http\Controllers\Admin\DestinationController::class, 'restore'])->name('destinations.restore');
    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::patch('packages/{package}/toggle-visibility', [\App\Http\Controllers\Admin\PackageController::class, 'toggleVisibility'])->name('packages.toggle-visibility');
    Route::patch('packages/{package}/approve', [\App\Http\Controllers\Admin\PackageController::class, 'approve'])->name('packages.approve');
    Route::patch('packages/{package}/reject', [\App\Http\Controllers\Admin\PackageController::class, 'reject'])->name('packages.reject');
    Route::get('packages-archived', [\App\Http\Controllers\Admin\PackageController::class, 'archived'])->name('packages.archived');
    Route::patch('packages/{package}/restore', [\App\Http\Controllers\Admin\PackageController::class, 'restore'])->name('packages.restore');
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('payments/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('payments/{payment}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');
    Route::patch('payments/{payment}/archive', [\App\Http\Controllers\Admin\PaymentController::class, 'archive'])->name('payments.archive');
    Route::patch('payments/{id}/restore', [\App\Http\Controllers\Admin\PaymentController::class, 'restore'])->name('payments.restore');
    Route::get('payments-archived', [\App\Http\Controllers\Admin\PaymentController::class, 'archived'])->name('payments.archived');
    Route::get('commissions', [\App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('commissions/report', [\App\Http\Controllers\Admin\CommissionController::class, 'report'])->name('commissions.report');
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);
    Route::patch('faqs/reorder', [\App\Http\Controllers\Admin\FaqController::class, 'reorder'])->name('faqs.reorder');
});

// Agent routes
Route::prefix('agent')->middleware(['auth', 'role:agent'])->name('agent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('packages', \App\Http\Controllers\Agent\PackageController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    Route::resource('bookings', \App\Http\Controllers\Agent\BookingController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Agent\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::resource('payments', \App\Http\Controllers\Agent\PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('payments/{payment}/archive', [\App\Http\Controllers\Agent\PaymentController::class, 'archive'])->name('payments.archive');
    Route::patch('payments/{id}/restore', [\App\Http\Controllers\Agent\PaymentController::class, 'restore'])->name('payments.restore');
    Route::get('payments-archived', [\App\Http\Controllers\Agent\PaymentController::class, 'archived'])->name('payments.archived');
    Route::get('customers', [\App\Http\Controllers\Agent\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [\App\Http\Controllers\Agent\CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [\App\Http\Controllers\Agent\CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}', [\App\Http\Controllers\Agent\CustomerController::class, 'show'])->name('customers.show');
    Route::patch('reviews/{review}/reply', [\App\Http\Controllers\Agent\CustomerController::class, 'replyReview'])->name('reviews.reply');
});

// Customer routes
Route::prefix('customer')->middleware(['auth', 'role:customer'])->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('bookings', \App\Http\Controllers\Customer\BookingController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Customer\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::resource('bookings.travelers', \App\Http\Controllers\Customer\TravelerController::class)->shallow();
    Route::resource('payments', \App\Http\Controllers\Customer\PaymentController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('reviews', \App\Http\Controllers\Customer\ReviewController::class)->only(['create', 'store', 'show', 'edit', 'update']);
});

// Legacy Breeze routes (keep for now)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Handle accidental GET /logout (e.g. bookmarks, direct URL)
Route::get('/logout', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

