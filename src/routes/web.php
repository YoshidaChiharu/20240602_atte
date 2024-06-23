<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

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

Route::get('/auth_first', [AuthController::class, 'showMailAnnounce']);
Route::post('/auth_first', [AuthController::class, 'authFirst']);

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/', [AttendanceController::class, 'punch']);
    Route::get('/attendance', [AttendanceController::class, 'showDaily'])->name('get.attendance');
    Route::post('/attendance', [AttendanceController::class, 'changeDay']);
    Route::get('/user_list', [AttendanceController::class, 'showUserList']);
    Route::get('/user_attendance_list/{user_id}', [AttendanceController::class, 'showUserAttendanceList'])->name('get.user_attendance_list');
    Route::post('/user_attendance_list/{user_id}', [AttendanceController::class, 'changeYearMonth']);
});
