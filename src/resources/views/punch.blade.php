@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/punch.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">{{$name}}さんお疲れ様です！</div>
    <div class="container__content">
        <div class="message">
            {{ session('message') }}
        </div>
        <form action="/" class="punch-form" method="post">
            @csrf
            <input type="submit" class="form__input" name="punch_in" value="勤務開始" {{ $status == 0 ? '' : 'disabled' }}>
            <input type="submit" class="form__input" name="punch_out" value="勤務終了" {{ $status == 1 ? '' : 'disabled' }}>
            <input type="submit" class="form__input" name="rest_in" value="休憩開始"  {{ $status == 1 ? '' : 'disabled' }}>
            <input type="submit" class="form__input" name="rest_out" value="休憩終了"  {{ $status == 2 ? '' : 'disabled' }}>
        </form>
    </div>
</div>
@endsection