<div id="top"></div>

## 目次

1. [アプリケーション概要](#アプリケーション概要)
2. [アプリケーションURL](#アプリケーションURL)
3. [機能一覧](#機能一覧)
4. [使用技術一覧](#使用技術一覧)
5. [テーブル設計](#テーブル設計)
6. [ER図](#ER図)
7. [環境構築手順](#環境構築手順)

## アプリケーション概要

アプリケーション名：Atte（アット）<br>
概要：企業の勤怠管理システム<br>
![Atte_top](/Atte_top.png)

## アプリケーションURL

- 開発環境：[http://localhost/](http://localhost/)
    - phpMyAdmin：[http://localhost:8888/](http://localhost:8888/)
- 本番環境：[http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com](http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com)

## 機能一覧

- 会員登録
- ログイン／ログアウト（ログイン時にメールでの2段階認証有り）
- 打刻機能（勤務開始/勤務終了/休憩開始/休憩終了）
- 日付別勤怠情報表示
- ユーザー一覧表示
- ユーザー別勤怠情報表示

## 使用技術一覧

| 言語・フレームワーク  | バージョン |
| --------------------- | ---------- |
| Laravel               | 8.83.27    |
| PHP                   | 8.3.7      |
| NGINX                 | 1.26.0     |
| MySQL                 | 8.0.37     |

## テーブル設計

![Atte_tables](/Atte_tables.png)

## ER図

![er_atte](/er_atte.png)

## 環境構築手順

1. **リモートリポジトリをクローンする**
```
git clone git@github.com:YoshidaChiharu/20240602_atte.git
```
2. **Dockerコンテナを作成**
```
docker-compose up -d --build
```
3. **`composer` コマンドでパッケージをインストール**
```
docker-compose exec php-fpm bash
```
```
composer install
```
4. **アプリケーションキーを生成**
```
php artisan key:generate
```
5. **`.env.dev` ファイル内の以下 `MAIL_PASSWORD` 部分に、別途共有済みのパスワードを記述**
```
MAIL_PASSWORD= #パスワードについては別途共有
```
6. **テーブル作成**
```
php artisan migrate
```
7. **ダミーデータ作成**
```
php artisan db:seed
```
```
exit
```
8. **(パーミッションエラーが出た場合)以下コマンドでパーミッションを変更**
```
sudo chmod -R 777 *
```

<p align="right">(<a href="#top">トップへ</a>)</p>
