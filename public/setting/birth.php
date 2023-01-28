<?php
session_start();
// セッションにログインIDが無ければ (=ログインされていない状態であれば) ログイン画面にリダイレクトさせる
if (empty($_SESSION['login_user_id'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  header("Location: /login.php");
  return;
}

// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
// セッションにあるログインIDから、ログインしている対象の会員情報を引く
$insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$insert_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $insert_sth->fetch();
if (isset($_POST['birth'])) {
  // フォームから name が送信されてきた場合の処理
  // ログインしている会員情報のnameカラムを更新する
  $insert_sth = $dbh->prepare("UPDATE users SET birth = :birth WHERE id = :id");
  $insert_sth->execute([
      ':id' => $user['id'],
      ':birth' => $_POST['birth'],
  ]);
  // 成功したら成功したことを示すクエリパラメータつきのURLにリダイレクト
  header("HTTP/1.1 302 Found");
  header("Location: ./birth.php?success=1");
  return;
}

$date = new DateTime();
?>

<!-- スマートフォンでも見やすいデザインに -->
<link href="./css/responsive.css" rel="stylesheet">
<meta name="viewport" content="width=device-width,initial-scale=1">

<a href="./index.php">設定一覧に戻る</a>

<h1>名前変更</h1>
<form method="POST">
  <input type="date" name="birth"
       value="<?= htmlspecialchars($user['birth']) ?>"
       min="1900-01-01" max="<?= $date->format('Y-m-d') ?>">
  <button type="submit">決定</button>
</form>
<?php if(!empty($_GET['success'])): ?>
<div>
  名前の変更処理が完了しました。
</div>
<?php endif; ?>
