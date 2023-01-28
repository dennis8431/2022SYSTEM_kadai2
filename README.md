# 2022システム開発 前期最終課題

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
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `image_filename` TEXT DEFAULT NULL
);
```

構築は以上となります。
http://54.209.52.190/bbsimagetest.php にアクセスして，動作を確認してください。

## 要件達成度
- [x] 会員登録&ログインした人のみが投稿できるサービスであること
- [x] 会員同士のフォロー機能があること
- [x] 自身がフォローしている人の投稿のみが時系列で表示される画面(=タイムライン)があること
- [x] 投稿には自由に画像を投稿できること（大きい画像もブラウザ側で自動で縮小してからサーバーにアップロードすること）
