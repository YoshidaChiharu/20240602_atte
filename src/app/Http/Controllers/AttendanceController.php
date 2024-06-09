<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 打刻ページ表示
    public function index() {
        $name = Auth::user()->name;
        return view('punch', compact('name'));
    }

    // 打刻処理
    public function punch(Request $request) {
        $auths = Auth::user();
        $name = $auths->name;

        if ($request->has('punch_in')) {
            $this->punchIn($auths->id);
        }elseif ($request->has('punch_out')) {
            $this->punchOut($auths->id);
        }elseif ($request->has('rest_in')) {
            $this->restIn($auths->id);
        }elseif ($request->has('rest_out')) {
            $this->restOut($auths->id);
        }

        return view('punch', compact('name'));
    }

    // 勤務開始
    private function punchIn($user_id) {
        $now = Carbon::now();

        $work = [
            "user_id" => $user_id,
            "work_on" => $now->format('Y-m-d'),
            "began_at" => $now->format('H:i:s'),
        ];

        Work::create($work);
    }

    // 勤務終了
    private function punchOut($user_id) {
        $now = Carbon::now();

        $work = ["finished_at" => $now->format('H:i:s')];
        Work::where('user_id', $user_id)
            ->where('work_on', $now->format('Y-m-d'))
            ->update($work);
    }

    // 休憩開始
    private function restIn($user_id) {

    }

    // 休憩終了
    private function restOut($user_id) {

    }
}
