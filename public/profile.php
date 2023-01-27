<?php
$user = null;
if (!empty($_GET['user_id'])) {
  $user_id = $_GET['user_id'];

  // DBに接続
  $dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
  // 対象の会員情報を引く
  $select_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
  $select_sth->execute([
      ':id' => $user_id,
  ]);
  $user = $select_sth->fetch();
}

if (empty($user)) {
  header("HTTP/1.1 404 Not Found");
  print("そのようなユーザーIDの会員情報は存在しません");
  return;
}

// 投稿データを取得。紐づく会員情報も結合し同時に取得する。
$select_sth = $dbh->prepare(
  'SELECT bbs_entries.*, users.name AS user_name, users.icon_filename AS user_icon_filename'
  . ' FROM bbs_entries INNER JOIN users ON bbs_entries.user_id = users.id'
  . ' WHERE user_id = :user_id'
  . ' ORDER BY bbs_entries.created_at DESC'
);
$select_sth->execute([
  ':user_id' => $user_id,
]);

// フォロー状態を取得
$relationship = null;
$follower_relationship = null;
session_start();
if (!empty($_SESSION['login_user_id'])) { // ログインしている場合
  // フォロー状態をDBから取得
  $select_sth2 = $dbh->prepare(
    "SELECT * FROM user_relationships"
    . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id"
  );
  $select_sth2->execute([
      ':followee_user_id' => $user['id'], // フォローされる側は閲覧しようとしているプロフィールの会員
      ':follower_user_id' => $_SESSION['login_user_id'], // フォローする側はログインしている会員
  ]);
  $relationship = $select_sth2->fetch();

   $select_sth2->execute([
      ':follower_user_id' => $user['id'], // フォローしている側は閲覧しようとしているプロフィールの会員
      ':followee_user_id' => $_SESSION['login_user_id'], // フォローされる側はログインしている会員
  ]);
  $follower_relationship = $select_sth2->fetch();
}
?>

<a href="/timeline.php">タイムラインに戻る</a>

<div style="
    width: 100%; height: 25em;
    <?php if(!empty($user['cover_filename'])): ?>
    background: url('/image/<?= $user['cover_filename'] ?>') center no-repeat;
    background-size: contain;
    margin-bottom: 15px;
    <?php endif; ?>
  ">
</div>

<div>
  <?php if(empty($user['icon_filename'])): ?>
  現在未設定
  <?php else: ?>
  <img src="/image/<?= $user['icon_filename'] ?>"
    style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">
  <?php endif; ?>
  <dl>
    <dd><?= htmlspecialchars($user['name']) ?>(ID: <?= $user['id'] ?>)</dd>
    <?php if(!empty($user['birth'])): ?>
      <?php
        $birth = DateTime::createFromFormat('Y-m-d', $user['birth']);
        $today = new DateTime('now');
        ?>
      <?= $today->diff($birth)->y ?>歳
    <?php endif; ?>
    <br>
    <dd>
      <?php if(empty($user['intro'])): ?>
       現在未設定
      <?php else: ?>
       <?= htmlspecialchars($user['intro']) ?>
      <?php endif; ?>
    </dd>
  </dl>
</div>

<?php if(!empty($_SESSION['login_user_id'])) { ?>
<?php if($user['id'] === $_SESSION['login_user_id']): // 自分自身の場合 ?>
<div style="margin: 1em 0;">
  これはあなたです！<br>
  <a href="/setting/index.php">設定画面はこちら</a>
</div>

<?php else: // 他人の場合 ?>
<div style="margin: 1em 0;">
  <?php if(empty($relationship)): // フォローしていない場合 ?>
  <div>
    <a href="./follow.php?followee_user_id=<?= $user['id'] ?>">フォローする</a>
  </div>
  <?php else: // フォローしている場合 ?>
  <div>
    <?= $relationship['created_at'] ?> にフォローしました。
  </div>
  <?php endif; ?>

  <?php if(!empty($follower_relationship)): // フォローされている場合 ?>
  <div>
    フォローされています。
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php } ?>

<hr>

<?php foreach($select_sth as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd>
      <?= htmlspecialchars($entry['body']) ?>
      <?php if(!empty($entry['image_filename'])): ?>
      <div>
        <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
      </div>
      <?php endif; ?>
    </dd>
  </dl>
<?php endforeach ?>
