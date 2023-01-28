<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
  header("HTTP/1.1 302 Found");
  header("Location: /login.php");
  return;
}

// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
// セッションにあるログインIDから、ログインしている対象の会員情報を引く
$select_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$select_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $select_sth->fetch();

if (isset($_POST['intro'])) {

  // ログインしている会員情報のnameカラムを更新する
  $update_sth = $dbh->prepare("UPDATE users SET intro = :intro WHERE id = :id");
  $update_sth->execute([
      ':id' => $user['id'],
      ':intro' => $_POST['intro'],
  ]);

  // 処理が終わったらリダイレクトする
  // リダイレクトしないと，リロード時にまた同じ内容でPOSTすることになる
  header("HTTP/1.1 302 Found");
  header("Location: ./introduction.php?success=1");
  return;
}

?>

<!-- スマートフォンでも見やすいデザインに -->
<link href="./css/responsive.css" rel="stylesheet">
<meta name="viewport" content="width=device-width,initial-scale=1">

<h1>自己紹介文設定/変更</h1>

<div>
  <?php if(empty($user['intro'])): ?>
  現在未設定
  <?php else: ?>
  <?= htmlspecialchars($user['intro']) ?>
  <?php endif; ?>
</div>

<form method="POST">
  <div style="margin: 1em 0;">
    <textarea name="intro" rows="4" cols="50" maxlength="1000" placeholder="Describe yourself here..."></textarea>
  </div>
  <button type="submit">アップロード</button>
</form>

<?php if(!empty($_GET['success'])): ?>
<div>
  自己紹介文の設定処理が完了しました。
</div>
<?php endif; ?>

<hr>

<a href="./index.php"><button>設定一覧に戻る</button></a>
