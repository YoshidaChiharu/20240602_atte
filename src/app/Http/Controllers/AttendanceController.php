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
        } elseif ($request->has('punch_out')) {
            $this->punchOut($auths);
        } elseif ($request->has('rest_in')) {
            $this->restIn($auths);
        } elseif ($request->has('rest_out')) {
            $this->restOut($auths);
        }

        return redirect('/');
    }

    // 日付別勤怠ページ表示
    public function attendance(Request $request) {
        if ($request->has('current')) {
            // 
        } else {
            $date = '2024-06-13';
            // $date = Carbon::now()->format('Y-m-d');
            $works = Work::where('work_on', $date)->paginate(5);
            $works->last()->workTime();
        }
        return view('attendance', compact(['date', 'works']));
    }

    // 勤務開始
    private function punchIn($user) {
        $now = Carbon::now();

        $param = [
            "user_id" => $user->id,
            "work_on" => $now->format('Y-m-d'),
            "began_at" => $now->format('H:i:s'),
        ];
        Work::create($param);
        // ステータス変更 [0:退勤中]->[1:勤務中]
        $user->update(['status' => '1']);
    }

    // 勤務終了
    private function punchOut($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        $work = $user->work->sortByDesc('work_on')->first();

        if($work->work_on > $date) {
            return false;
        } elseif($work->work_on === $date) {
            // 同日中の退勤処理
            $work->update(["finished_at" => $time]);
        } elseif($work->work_on < $date) {
            // 日跨ぎ処理
            $work->update(["finished_at" => "23:59:59"]);
            $param = [
                "user_id" => $user->id,
                "work_on" => $date,
                "began_at" => "00:00:00",
                "finished_at" => $time,
            ];
            Work::create($param);
        }
        // ステータス変更 [1:勤務中]->[0:退勤中]
        $user->update(['status' => 0]);
    }

    // 休憩開始
    private function restIn($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        $work = $user->work->sortByDesc('work_on')->first();

        if($work->work_on > $date) {
            return false;
        } elseif($work->work_on === $date) {
            // 同日中の休憩開始処理
            $param = [
                'work_id' => $work->id,
                "began_at" => $time,
            ];
            Rest::create($param);
        } elseif($work->work_on < $date) {
            // 日跨ぎ処理
            $work->update(["finished_at" => "23:59:59"]);
            $param = [
                "user_id" => $user->id,
                "work_on" => $date,
                "began_at" => "00:00:00",
            ];
            $work_today = Work::create($param);
            $param = [
                'work_id' => $work_today->id,
                "began_at" => $time,
            ];
            Rest::create($param);
        }
        // ステータス変更 [1:勤務中]->[2:休憩中]
        $user->update(['status' => 2]);
    }

    // 休憩終了
    private function restOut($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        $work = $user->work->sortByDesc('work_on')->first();
        $rest = $work->rest->sortByDesc('id')->first();

        if($work->work_on > $date) {
            return false;
        } elseif($work->work_on === $date) {
            // 同日中の休憩終了処理
            $rest->update(["finished_at" => $time]);
        } elseif($work->work_on < $date) {
            // 日跨ぎ処理
            $rest->update(["finished_at" => "23:59:59"]);
            $work->update(["finished_at" => "23:59:59"]);
            $param = [
                "user_id" => $user->id,
                "work_on" => $date,
                "began_at" => "00:00:00",
            ];
            $work_today = Work::create($param);
            $param = [
                'work_id' => $work_today->id,
                "began_at" => "00:00:00",
                "finished_at" => $time,
            ];
            Rest::create($param);
        }
        // ステータス変更 [2:休憩中]->[1:勤務中]
        $user->update(['status' => 1]);
    }
}
