@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/punch.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">nameさんお疲れ様です！</div>
    <div class="container__content">
        <form action="/" class="punch-form" method="post">
            @csrf
            <input type="submit" class="form__input" name="punch_in" value="勤務開始">
            <input type="submit" class="form__input" name="punch_out" value="勤務終了">
            <input type="submit" class="form__input" name="rest_in" value="休憩開始">
            <input type="submit" class="form__input" name="rest_out" value="休憩終了">
        </form>
    </div>
</div>
@endsection