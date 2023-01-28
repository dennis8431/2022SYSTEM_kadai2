# 2022システム開発 後期最終課題

## サービス構築手順書

### 1. Dockerの起動

以下のコマンドでDockerfileというファイルにあるリポジトリまで移動して、

Dockerを起動してください。
```
cd 2022SYSTEM_kadai1
docker compose build
docker compose up
```
### 2. テーブルの作成

以下のコマンドでMySQLを起動してください。

```
docker compose exec mysql mysql techc
```
以下のコマンドでbbs_entriesというテーブルを作成します。

```
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `body` TEXT NOT NULL,
    `image_filename` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
```
CREATE TABLE `user_relationships` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `followee_user_id` INT UNSIGNED NOT NULL,
    `follower_user_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
```
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    `icon_filename` TEXT DEFAULT NULL
    `cover_filename` TEXT DEFAULT NULL
);
```

構築は以上となります。
http://54.159.155.67/login.php にアクセスして，動作を確認してください。

## 要件達成度
- [x] 会員登録&ログインした人のみが投稿できるサービスであること
- [x] 会員同士のフォロー機能があること
- [x] 自身がフォローしている人の投稿のみが時系列で表示される画面(=タイムライン)があること
- [x] 投稿には自由に画像を投稿できること（大きい画像もブラウザ側で自動で縮小してからサーバーにアップロードすること）
- [ ] タイムラインを無限スクロールにすること
- [ ] 投稿に対して画像を1枚だけではなく複数枚(最大4枚)付けれるようにすること
- [x] CSSを使って、スマートフォンでも見やすいデザインに
- [x] UIを工夫し、タイムライン、プロフィールページ、その他たくさんあるページの行き来をしやすく
