<div id="top"></div>

## 目次

1. [プロジェクト概要](#プロジェクト概要)
2. [使用技術一覧](#使用技術一覧)
3. [環境](#環境)
4. [環境構築手順](#環境構築手順)
5. [ER図](#ER図)
6. [URL](#URL)

## プロジェクト概要

勤怠打刻システム「Atte」開発プロジェクト

## 使用技術一覧

- Laravel
- PHP
- NGINX
- MYSQL

## 環境

| 言語・フレームワーク  | バージョン |
| --------------------- | ---------- |
| Laravel               | 8.83.8     |
| PHP                   | 8.3.7      |
| NGINX                 | 1.26.0     |
| MySQL                 | 8.0.37     |

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

## ER図

![er-atte](/er-atte.png)

## URL

- 開発環境：[http://localhost:8888/](http://localhost:8888/)
- phpMyAdmin：[http://localhost/](http://localhost/)

<p align="right">(<a href="#top">トップへ</a>)</p>
