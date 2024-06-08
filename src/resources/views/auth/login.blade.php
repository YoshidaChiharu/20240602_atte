@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">ログイン</div>
    <div class="container__content">
        <form action="/login" class="login-form" method="post">
            @csrf
            <div class="form__group">
                <input type="text" class="form__group-input" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
                <div class="form__group-error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <input type="text" class="form__group-input" name="password" placeholder="パスワード">
                <div class="form__group-error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit">ログイン</button>
            </div>
        </form>
        <div class="register">
            <p class="register-text">アカウントをお持ちでない方はこちらから</p>
            <a href="/register" class="register-link">会員登録</a>
        </div>
    </div>
</div>
@endsection