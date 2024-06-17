<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/', [AttendanceController::class, 'punch']);
    Route::get('/attendance', [AttendanceController::class, 'showDaily'])->name('show.daily');
    Route::post('/attendance', [AttendanceController::class, 'changeDay']);
    Route::get('/user_list', [AttendanceController::class, 'showUserList']);
    Route::get('/user_attendance_list/{id}', [AttendanceController::class, 'showUserAttendanceList']);
});
