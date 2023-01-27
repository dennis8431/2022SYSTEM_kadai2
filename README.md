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
- [x] 投稿者が自由にテキストを投稿できること
- [x] 内容はMySQLに保存すること
- [x] XSSおよびSQLインジェクションの対策ができていること
- [x] 投稿それぞれに自動で投稿日時が付与されていること
- [x] 投稿それぞれに自動で連番が付与されていること
- [x] 投稿者が自由に画像を投稿できること
- [x] 5MB以上の画像をアップロードできないように
- [ ] 画像をブラウザ側(JavaScriptで実装)で5MB以下に自動縮小しアップロードするように (失敗した)
- [x] 投稿それぞれに自動で付与されている連番を各投稿に表示し，レスアンカー機能が使えるように (public/bbsimagetest.php:38)
- [x] CSSを使って，スマートフォンでも見やすいデザインに (public/bbsimagetest.php:59)
