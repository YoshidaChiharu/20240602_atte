<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AttendanceController extends Controller
{
    // 打刻ページ表示
    public function index() {
        $name = Auth::user()->name;
        return view('punch', compact('name'));
    }
}
