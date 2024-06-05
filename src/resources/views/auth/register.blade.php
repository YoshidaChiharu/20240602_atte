@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">会員登録</div>
    <div class="container__content">
        <form action="/register" class="register-form" method="post">
            @csrf
            <div class="form__group">
                <input type="text" class="form__group-input" name="name" placeholder="名前" value="{{ old('name') }}">
                <div class="form__group-error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
                </div>
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
            <div class="form__group">
                <input type="text" class="form__group-input" name="password_confirmation" placeholder="確認用パスワード">
                <div class="form__group-error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit">会員登録</button>
            </div>
        </form>
        <div class="login">
            <p class="login-text">アカウントをお持ちの方はこちらから</p>
            <a href="#" class="login-link">ログイン</a>
        </div>
    </div>
</div>
@endsection