<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function showDaily(Request $request) {
        $date = $request->date;
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $works = Work::where('work_on', $date)->paginate(5);

        return view('attendance', compact(['date', 'works']));
    }

    // 日付別勤怠ページ：日付前後操作
    public function changeDay(Request $request) {
        $date = new Carbon($request->current);
        if ($request->has('prev')) {
            $date = $date->subDays(1)->format('Y-m-d');
        } elseif ($request->has('next')) {
            $date = $date->addDays(1)->format('Y-m-d');
        }
        return redirect()->route('show.daily', compact('date'));
    }

    // ユーザー一覧ページ表示
    public function showUserList(Request $request) {
        $users = User::all();
        foreach($users as $user) {
            $work = $user->work->sortByDesc('work_on')->first();
            if(empty($work)){
                $last_work = '-';
            } else {
                $last_work = "{$work->work_on} {$work->began_at}";
            }

            $user_list[] = [
                'id' => $user->id,
                'name' => $user->name,
                'last_work' => $last_work,
            ];
        }
        array_multisort(array_column($user_list, 'last_work'), SORT_DESC, $user_list);
        for($i = 0; $i < count($user_list); $i++) {
            $user_list[$i]['no'] = $i+1;
        }

        // ページネーション
        $perPage = 20;
        $user_list = collect($user_list);
        $user_list = new LengthAwarePaginator(
            $user_list->forPage($request->page, $perPage),
            $user_list->count(),
            $perPage,
            $request->page,
            ['path' => $request->url()],
        );
        // dd($user_list);

        return view('user_list', compact('user_list'));
    }

    // 勤務開始メソッド
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

    // 勤務終了メソッド
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

    // 休憩開始メソッド
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

    // 休憩終了メソッド
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
