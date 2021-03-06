<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];
$errors = array();
$enteredSentence = array();

$dbh = connectDb();
$sql = "select * from posts where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// 存在しないidを指定された場合はindex.phpに飛ばす
if (!$post) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $body = $_POST['body'];
  $title = $_POST['title'];

  // バリデーション
  if (empty($title)) {
    $errors['title'] = 'タイトルが未入力です';
    $enteredSentence[] = $body;
  }

  if (empty($body)) {
    $errors['body'] = 'メッセージが未入力です';
  }

  if ($post['title'] == $title && $post['body'] == $body) {
    $errors[] = 'タイトルかメッセージどちらかを変更して下さい。';
  }

  // バリデーション突破後
  if (empty($errors)) {
    $dbh = connectDb();
    $sql = "update posts set title = :title, body = :body, updated_at = now()
            where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":body", $body);
    $stmt->execute();

    header('Location: show.php?id='.$id);
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>編集｜Blogs!</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="row top-nav-bar">
    <div class="columns large-3">
      <h2 class="top-nav-bar-title">ブログ管理APP</h2>
    </div>
    <div class="columns large-4">
      <a class="top-nav-bar-add" href="add.php">記事を書く</a>
    </div>
  </div>
  <div class="row">
    <div class="columns large-12">
      <?php if ($errors) : ?>
        <div class="add-error callout alert">
          <h5>エラーがあります</h5>

          <ul>
            <?php foreach ($errors as $error) : ?>
              <li>
                <?php echo h($error); ?>
              </li>
            <?php endforeach; ?>
          </ul>

        </div>
      <?php endif; ?>
      <h1>記事編集</h1>
      <a class="button return-button" href="show.php?id=<?php echo h($post['id']) ?>"><i class="fa fa-angle-left"></i>戻る</a>
      <form action="" method="post">
        <p>
          <label>タイトル
            <input type="text" name="title" value="<?php echo h($post['title']); ?>">
          </label>
        </p>
        <p>
          <label>本文
            <textarea name="body" cols="70" rows="5"><?php echo h($post['body']) ?></textarea>
          </label>
        </p>
        <p><input class="expanded button" type="submit" value="投稿する"></p>
      </form>
    </div>
  </div>
  <script src="js/vendor/jquery.min.js"></script>
  <script src="js/vendor/what-input.min.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>