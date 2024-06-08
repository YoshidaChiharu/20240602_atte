<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // 打刻ページ表示
    public function index() {
        return view('attendance');
    }
}
