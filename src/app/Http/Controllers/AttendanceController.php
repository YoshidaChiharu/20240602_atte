<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Lang;

class AttendanceController extends Controller
{
    // 打刻ページ表示 ==================================================
    public function index(Request $request) {
        $auths = Auth::user();
        $name = $auths->name;
        $status = $auths->status;
        return view('punch', compact(['name', 'status']));
    }

    // 打刻ページ：打刻処理 ============================================
    public function punch(Request $request) {
        $auths = Auth::user();

        if ($request->has('punch_in')) {
            $result = $this->punchIn($auths);
        } 
        if ($request->has('punch_out')) {
            $result = $this->punchOut($auths);
        } 
        if ($request->has('rest_in')) {
            $result = $this->restIn($auths);
        } 
        if ($request->has('rest_out')) {
            $result = $this->restOut($auths);
        }
        
        // 例外処理エラーメッセージ
        if ($result['result']) {
            $message = null;
        } else {
            switch ($result['error_code']) {
                case 1062:
                    $message = Lang::get('message.ERR_DOUBLE_STAMP');
                    break;
                default:
                    $message = Lang::get('message.ERR_UNKNOWN');
            }
        }

        return redirect('/')->with('message', $message);
    }

    // 日付別勤怠ページ表示 ============================================
    public function showDaily(Request $request) {
        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        $works = Work::where('work_on', $date)->paginate(5);

        return view('attendance', compact(['date', 'works']));
    }

    // 日付別勤怠ページ：日付変更操作 ==================================
    public function changeDay(Request $request) {
        $date = new Carbon($request->current);
        if ($request->has('prev')) {
            $date = $date->subDays(1)->format('Y-m-d');
        } elseif ($request->has('next')) {
            $date = $date->addDays(1)->format('Y-m-d');
        }
        return redirect()->route('get.attendance', compact('date'));
    }

    // ユーザー一覧ページ表示 ==========================================
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

        return view('user_list', compact('user_list'));
    }

    // ユーザー別勤怠ページ表示 ========================================
    public function showUserAttendanceList(Request $request, $user_id) {
        if ($request->has('year_month')) {
            $carbon = new Carbon($request->year_month);
        } else {
            $carbon = Carbon::now();
        }
        $year = $carbon->format('Y');
        $month = $carbon->format('m');

        $user = User::find($user_id);
        $user_name = $user->name;
        $monthly_works = $user->getMonthlyWorks($year, $month);
        $last_day = (new Carbon("{$year}-{$month}"))->endOfMonth()->format('d');

        for ($day = 1, $i = 0; $day <= $last_day; $day++) {
            $day = sprintf('%02d', $day);
            if ($monthly_works->isNotEmpty() &&
                $monthly_works[$i]->work_on === "{$year}-{$month}-{$day}") {
                $works[] = [
                    'date' => "{$month}/{$day}",
                    'began_at' => $monthly_works[$i]->began_at,
                    'finished_at' => $monthly_works[$i]->finished_at,
                    'rest_time' => $monthly_works[$i]->restTime(),
                    'work_time' => $monthly_works[$i]->workTime(),
                ];
                if($i < ($monthly_works->count() - 1)) { $i++; }
            } else {
                $works[] = [
                    'date' => "{$month}/{$day}",
                    'began_at' => '-',
                    'finished_at' => '-',
                    'rest_time' => '-',
                    'work_time' => '-',
                ];
            }
        }

        return view(
            'user_attendance_list',
            compact(['user_id', 'user_name', 'year', 'month', 'works']),
        );
    }

    // ユーザー別勤怠ページ：年月変更操作 ================================
    public function changeYearMonth(Request $request, $user_id) {
        if ($request->has('prev')) {
            $year_month = (new Carbon($request->current))->subMonth();
        } elseif ($request->has('next')) {
            $year_month = (new Carbon($request->current))->addMonth();
        } elseif ($request->has('specify')) {
            $year_month = new Carbon($request->specific_month);
        }
        $year_month = $year_month->format('Y-m');

        return redirect()->route('get.user_attendance_list', compact('user_id', 'year_month'));
    }

    // 勤務開始メソッド ================================================
    private function punchIn($user) {
        $now = Carbon::now();

        $param = [
            "user_id" => $user->id,
            "work_on" => $now->format('Y-m-d'),
            "began_at" => $now->format('H:i:s'),
        ];
        try {
            Work::create($param);
            // ステータス変更 [0:退勤中]->[1:勤務中]
            $user->update(['status' => '1']);
            return ['result' => true];
        }
        catch (\Exception $e) {
            return [
                'result' => false,
                'error_code' => $e->errorInfo[1],
            ];
        }
    }

    // 勤務終了メソッド ================================================
    private function punchOut($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        try {
            $work = $user->work->sortByDesc('work_on')->first();
            if($date === $work->work_on) {
                // 同日中の退勤処理
                $work->update(["finished_at" => $time]);
                // ステータス変更 [1:勤務中]->[0:退勤中]
                $user->update(['status' => 0]);
                return ['result' => true];
            }
            if($date > $work->work_on) {
                // 日跨ぎ処理
                $work->update(["finished_at" => "23:59:59"]);
                $param = [
                    "user_id" => $user->id,
                    "work_on" => $date,
                    "began_at" => "00:00:00",
                    "finished_at" => $time,
                ];
                Work::create($param);
                // ステータス変更 [1:勤務中]->[0:退勤中]
                $user->update(['status' => 0]);
                return ['result' => true];
            }
            if($date < $work->work_on) {
                // 予期せぬエラー
                return ['result' => false];
            }
        }
        catch (\Exception $e) {
            return [
                'result' => false,
                'error_code' => $e->errorInfo[1],
            ];
        }
    }

    // 休憩開始メソッド ================================================
    private function restIn($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        try {
            $work = $user->work->sortByDesc('work_on')->first();
            if($date === $work->work_on) {
                // 同日中の休憩開始処理
                $param = [
                    'work_id' => $work->id,
                    "began_at" => $time,
                ];
                Rest::create($param);
                // ステータス変更 [1:勤務中]->[2:休憩中]
                $user->update(['status' => 2]);
                return ['result' => true];
            }
            if($date > $work->work_on) {
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
                // ステータス変更 [1:勤務中]->[2:休憩中]
                $user->update(['status' => 2]);
                return ['result' => true];
            }
            if($date < $work->work_on) {
                // 予期せぬエラー
                return ['result' => false];
            }
        }
        catch (\Exception $e) {
            return [
                'result' => false,
                'error_code' => $e->errorInfo[1],
            ];
        }
    }

    // 休憩終了メソッド=================================================
    private function restOut($user) {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        try {
            $work = $user->work->sortByDesc('work_on')->first();
            $rest = $work->rest->sortByDesc('id')->first();
            if($date === $work->work_on) {
                // 同日中の休憩終了処理
                $rest->update(["finished_at" => $time]);
                // ステータス変更 [2:休憩中]->[1:勤務中]
                $user->update(['status' => 1]);
                return ['result' => true];
            }
            if($date > $work->work_on) {
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
                // ステータス変更 [2:休憩中]->[1:勤務中]
                $user->update(['status' => 1]);
                return ['result' => true];
            }
            if($date < $work->work_on) {
                // 予期せぬエラー
                return ['result' => false];
            }
        }
        catch (\Exception $e) {
            return [
                'result' => false,
                'error_code' => $e->errorInfo[1],
            ];
        }
    }
}
