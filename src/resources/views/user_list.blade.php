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
            @foreach($user_list as $user)
            <tr>
                <td class="table__column-no">{{ $user['no'] }}</td>
                <td class="table__column-name">{{ $user['name'] }}</td>
                <td class="table__column-datetime">{{ $user['last_work'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="user-list__pagination">
        {{ $user_list->links('vendor.pagination.default') }}
    </div>
</div>
@endsection