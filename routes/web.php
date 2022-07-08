<?php

use Illuminate\Support\Facades\Route;

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
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view('hackathons', 'hackathons.index')->name('hackathons');
});
/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('user')->name('user/')->group(static function() {
            Route::get('/', App\Http\Livewire\Users\Users::class)->name('index');
        });
    });
});/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('permission')->name('permission/')->group(static function() {
            Route::get('/', App\Http\Livewire\Permissions\Permissions::class)->name('index');
        });
    });
});/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('role')->name('role/')->group(static function() {
            Route::get('/', App\Http\Livewire\Roles\Roles::class)->name('index');
        });
    });
});/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('activitylog')->name('activitylog/')->group(static function() {
            Route::get('/', App\Http\Livewire\ActivityLogs\ActivityLogs::class)->name('index');
        });
    });
});/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('hackthon')->name('hackthon/')->group(static function() {
            Route::get('/', App\Http\Livewire\Hackthons\Hackthons::class)->name('index');
        });
    });
});