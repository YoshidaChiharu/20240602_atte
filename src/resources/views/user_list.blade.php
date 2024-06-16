@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="user-list">
        <table class="user-list__table">
            <tr>
                <th class="table__column-no">No.</th>
                <th class="table__column-name">名前</th>
                <th class="table__column-datetime">最終出勤日時</th>
            </tr>

            @foreach($user_list as $data)
            <tr>
                <td class="table__column-no">{{ $data['no'] }}</td>
                <td class="table__column-name">{{ $data['name'] }}</td>
                <td class="table__column-datetime">{{ $data['last_work'] }}</td>
            </tr>
            @endforeach

            <!-- <tr>
                <td class="table__column-no">1</td>
                <td class="table__column-name">test</td>
                <td class="table__column-datetime">2024-06-16 00:00:00</td>
            </tr>
            <tr>
                <td class="table__column-no">2</td>
                <td class="table__column-name">test</td>
                <td class="table__column-datetime">2024-06-16 00:00:00</td>
            </tr>
            <tr>
                <td class="table__column-no">3</td>
                <td class="table__column-name">test</td>
                <td class="table__column-datetime">2024-06-16 00:00:00</td>
            </tr> -->
        </table>
    </div>
    <div class="user-list__pagination">
    </div>
</div>
@endsection