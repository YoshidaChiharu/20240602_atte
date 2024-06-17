@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_attendance_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="heading">nameさんの出勤簿</div>
    <div class="selector">
        <form action="/user_attendance_list" method="post">
            @csrf
            <div class="selector-center">
                <input type="hidden" value="" name="current">
                <button name="prev"><</button>
                <span>2024年06月度</span>
                <button name="next">></button>
            </div>
            <div class="selector-right">
                <span>年月指定</span>
                <input type="month" name="year_month" value="2024-06">
                <button>表示</button>
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

            <tr>
                <td>2024-06-01</td>
                <td>10:00:00</td>
                <td>19:00:00</td>
                <td>01:00:00</td>
                <td>08:00:00</td>
            </tr>
            <tr>
                <td>2024-06-02</td>
                <td>10:00:00</td>
                <td>19:00:00</td>
                <td>01:00:00</td>
                <td>08:00:00</td>
            </tr>
            <tr>
                <td>2024-06-03</td>
                <td>10:00:00</td>
                <td>19:00:00</td>
                <td>01:00:00</td>
                <td>08:00:00</td>
            </tr>

        </table>
    </div>
</div>
@endsection