@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/mail_announce.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container__heading">ログイン認証メールを送信しました</div>
    <div class="container__content">
        <div class="announce-text">
            <p>
                ご登録のメールアドレス&nbsp;{{ session('url') }}&nbsp;へログイン認証用のメールを送信しました。<br>
                ご確認いただき、メールに記載された URL をクリックして、ログインを完了してください。
            </p>
        </div>
        <div class="notes-text">
            <p class="notes-text__heading">&lt;メールが届かない場合&gt;</p>
            <ul>
                <li>迷惑メールフォルダに振り分けられていたり、フィルターや転送の設定によって受信ボックス以外の場所に保管されていないかご確認ください</li>
                <li>メールの配信に時間がかかる場合がございます。数分程度待った上で、メールが届いていないか再度ご確認ください</li>
                <li>
                    登録時にご使用のメールアドレスが正しいかご確認ください。正しくない場合は再度「会員登録」からやり直してください
                    <div class="register">
                        <a href="/register" class="register-link">&gt;&nbsp;会員登録</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection