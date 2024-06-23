<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    // メール送信済みページ表示 ================================
    public function showMailAnnounce() {
        return view('auth.mail_announce');
    }

    // 認証処理：第1段階目 ================================
    public function authFirst(LoginRequest $request) {
        // dd($request->email, $request->password);
        $user = User::where('email', $request->email)->first();

        if ($user &&
            Hash::check($request->password, $user->password)) {
            // token発行
            // メール送信

            return redirect('/auth_first');
        } else {
            return redirect('/login')->with('message', '一致するユーザーが見つかりません');;
        }
    }
}
