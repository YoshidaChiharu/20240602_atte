@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="selector">
        <form action="/attendance" method="get">
            <input type="hidden" value="2024-06-13">
            <button class="selector__prev" name="prev"><</button>
            <span class="selector__current">2024-06-13</span>
            <button class="selector__next" name="next">></button>
        </form>
    </div>
    <div class="attendance-info">
        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <th>勤務開始</th>
                <th>勤務終了</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
            </tr>
            <tr>
                <td>テスト太郎</td>
                <td>10:00:00</td>
                <td>20:00:00</td>
                <td>00:30:00</td>
                <td>09:30:00</td>
            </tr>
            <tr>
                <td>テスト太郎</td>
                <td>10:00:00</td>
                <td>20:00:00</td>
                <td>00:30:00</td>
                <td>09:30:00</td>
            </tr>
            <tr>
                <td>テスト太郎</td>
                <td>10:00:00</td>
                <td>20:00:00</td>
                <td>00:30:00</td>
                <td>09:30:00</td>
            </tr>
            <tr>
                <td>テスト太郎</td>
                <td>10:00:00</td>
                <td>20:00:00</td>
                <td>00:30:00</td>
                <td>09:30:00</td>
            </tr>
            <tr>
                <td>テスト太郎</td>
                <td>10:00:00</td>
                <td>20:00:00</td>
                <td>00:30:00</td>
                <td>09:30:00</td>
            </tr>
        </table>
    </div>
    <div class="pagination">
    </div>
</div>
@endsection