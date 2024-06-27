@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_attendance_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading">{{ $user_name }} さんの出勤簿</div>
    <div class="selector">
        <div class="selector-center">
            <form action="/user_attendance_list/{{ $user_id }}" method="get">
                <button name="prev" value="{{ $prev_year_month }}"><</button>
                <span>{{ $year }}年{{ $month }}月度</span>
                <button name="next" value="{{ $next_year_month }}">></button>
            </form>
        </div>
        <div class="selector-right">
            <form action="/user_attendance_list/{{ $user_id }}" method="get">
                <span>年月指定</span>
                <input type="month" name="specific_month" value="{{ $year }}-{{ $month }}">
                <button name="specify">表示</button>
            </form>
        </div>
    </div>
    <div class="attendance-info">
        <table class="attendance-table">
            <tr>
                <th>日付</th>
                <th>勤務開始</th>
                <th>勤務終了</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
            </tr>
            @foreach($works as $work)
            <tr>
                <td>{{ $work['date'] }}</td>
                <td>{{ $work['began_at'] }}</td>
                <td>{{ $work['finished_at'] }}</td>
                <td>{{ $work['rest_time'] }}</td>
                <td>{{ $work['work_time'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection