@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_attendance_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading">{{ $user_name }} さんの出勤簿</div>
    <div class="selector">
        <form action="/user_attendance_list/{{ $user_id }}" method="post">
            @csrf
            <div class="selector-center">
                <input type="hidden" name="current" value="{{ $year }}-{{ $month }}">
                <button name="prev"><</button>
                <span>{{ $year }}年{{ $month }}月度</span>
                <button name="next">></button>
            </div>
            <div class="selector-right">
                <span>年月指定</span>
                <input type="month" name="specific_month" value="{{ $year }}-{{ $month }}">
                <button name="specify">表示</button>
            </div>
        </form>
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