<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 打刻ページ表示
    public function index() {
        $auths = Auth::user();
        $name = $auths->name;
        $status = $auths->status;
        return view('punch', compact(['name', 'status']));
    }

    // 打刻処理
    public function punch(Request $request) {
        $auths = Auth::user();

        if ($request->has('punch_in')) {
            $this->punchIn($auths);
        }elseif ($request->has('punch_out')) {
            $this->punchOut($auths);
        }elseif ($request->has('rest_in')) {
            $this->restIn($auths);
        }elseif ($request->has('rest_out')) {
            $this->restOut($auths);
        }

        $name = $auths->name;
        $status = $auths->status;
        return view('punch', compact(['name', 'status']));
    }

    // 勤務開始
    private function punchIn($user) {
        $now = Carbon::now();

        $work = [
            "user_id" => $user->id,
            "work_on" => $now->format('Y-m-d'),
            "began_at" => $now->format('H:i:s'),
        ];
        Work::create($work);
        $user->update(['status' => '1']);
    }

    // 勤務終了
    private function punchOut($user) {
        $now = Carbon::now();

        $user->work
            ->sortByDesc('work_on')
            ->first()
            ->update(["finished_at" => $now->format('H:i:s')]);

        $user->update(['status' => 0]);
    }

    // 休憩開始
    private function restIn($user) {
        $now = Carbon::now();

        $work = $user->work
                    ->sortByDesc('work_on')
                    ->first();
        $rest = [
            'work_id' => $work->id,
            "began_at" => $now->format('H:i:s'),
        ];
        Rest::create($rest);
        $user->update(['status' => 2]);
    }

    // 休憩終了
    private function restOut($user) {
        $now = Carbon::now();

        $user->work
            ->sortByDesc('work_on')
            ->first()
            ->rest
            ->sortByDesc('id')
            ->first()
            ->update(["finished_at" => $now->format('H:i:s')]);
        $user->update(['status' => 1]);
    }
}
