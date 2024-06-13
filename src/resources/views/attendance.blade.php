@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="selector">
        <form action="/attendance" method="get">
            <input type="hidden" value="{{ $date }}" name="current">
            <button class="selector__prev" name="prev"><</button>
            <span class="selector__current">{{ $date }}</span>
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
            @foreach($works as $work)
            <tr>
                <td>{{ $work->user->name }}</td>
                <td>{{ $work->began_at }}</td>
                <td>{{ $work->finished_at }}</td>
                <td>{{ $work->restTime() }}</td>
                <td>{{ $work->workTime() }}</td>
            </tr>
            @endforeach
            <!-- <tr>
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
            </tr> -->
        </table>
    </div>
    <div class="pagination">
        {{ $works->appends(request()->query())->links('vendor.pagination.default') }}
    </div>
</div>
@endsection