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
docker-compose exec php bash
```
```
composer install
```
4. **`.env` ファイルを作成し、アプリケーションキーを生成**
```
cp .env.example .env
php artisan key:generate
```
5. **`.env` ファイル内の環境変数を以下の通り変更**
```
// 前略

DB_CONNECTION=mysql
DB_HOST=mysql             # 書き換え箇所
DB_PORT=3306
DB_DATABASE=laravel_db    # 書き換え箇所
DB_USERNAME=laravel_user  # 書き換え箇所
DB_PASSWORD=laravel_pass  # 書き換え箇所

// 後略
```
6. **テーブル作成**
```
php artisan migrate
```
7. **ダミーデータ作成**
```
php artisan db:seed
```

<p align="right">(<a href="#top">トップへ</a>)</p>
