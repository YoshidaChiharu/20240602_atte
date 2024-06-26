@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/auth_error.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">認証エラー</div>
    <div class="container__content">
        <p class="error-text">{{ session('message') }}</p>
        <div class="login">
            <p class="login-text">再度ログインをお試しください</p>
            <a href="/login" class="login-link">ログイン</a>
        </div>
    </div>
</div>
@endsection