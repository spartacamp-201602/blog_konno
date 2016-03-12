<?php
// 設定ファイルと関数ファイルを読み込む
require_once('config.php');
require_once('functions.php');

// DBに接続
$dbh = connectDb();

// ブログ記事を取得（記事が新しい順）
$sql = "select * from posts order by created_at desc";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Blogs!</title>
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
  <div class="column row top-title">
    <h1>Original Blog</h1>
  </div>
  <div class="top-bar">
    <div class="row">
      <div class="large-4 columns top-bar-cate">
        <a href="index.php">Home</a>
      </div>
      <div class="large-4 columns top-bar-cate">
        <a class="">About</a>
      </div>
      <div class="large-4 columns top-bar-cate">
        <a class="">Contact</a>
      </div>
    </div>
  </div>
  <div class="row main-sec">
    <div class="columns large-8 article-sec">
      <h3 class="article-sec-title">新着記事</h3>
      <ul class="article-list">
        <?php if (!empty($posts)) : ?>
          <?php foreach ($posts as $post) : ?>
            <li>
              <span class="article-update"><i class="fa fa-clock-o"></i><?php echo h($post['created_at']) ?></span>
              <a href="show.php?id=<?= h($post['id']) ?>"><h4><?php echo h($post['title']) ?></h4><br></a>
              <p><?php echo h($post['body']) ?></p>
            </li>
          <?php endforeach ?>
        <?php endif ?>
      </ul>
    </div>
    <div class="columns large-4 side-menu-sec">
      <div class="profile-sec side-menu-sub-sec">
        <span class="side-menu-title">プロフィール</span>
        <div class="row profile-main">
          <div class="columns large-3 profile-main-img">
            <img src="img/azunobu.png">
          </div>
          <div class="columns large-9 profile-main-name">
            <h5>今野 遼太</h5>
          </div>
        </div>
      </div>
      <div class="new-article-sec side-menu-sub-sec">
        <span class="side-menu-title">最新記事</span>
        <div class="new-article-main">
          <ul class="new-article-list">
            <?php if (!empty($posts)) : ?>
              <?php foreach ($posts as $post) : ?>
                <li>
                  <a href="show.php?id=<?php echo h($post['id']) ?>"><?php echo h($post['title']) ?></a><br>
                </li>
              <?php endforeach ?>
            <?php endif ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script src="js/vendor/jquery.min.js"></script>
  <script src="js/vendor/what-input.min.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>