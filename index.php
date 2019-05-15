<?php

include_once './class/start.php';

function h($s) {
  echo htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function setToken() {
  $token_post = sha1(uniqid(mt_rand(), true));
  $_SESSION['token_post'] = $token_post;
}

function checkToken() {
  if (empty($_SESSION['token_post']) || ($_SESSION['token_post'] != $_POST['token_post'])) {
    echo "WARNING!!!";
    exit;
  }
}



//  データを所得
$query = "SELECT * FROM `posts`";
if (!$posts = mysqli_query($link, $query)) {
  die("テーブルへの接続に失敗しました。\n\n");
}


if (array_key_exists('postType', $_POST)) {
  checkToken();

  if ($_POST['postType'] === 'new') {
    if (array_key_exists('text', $_POST)) {

      //  データが入力されているかを確認する
      if ($_POST['text'] === '') {
        echo "何も入力されていません！";
      } else {


        //  ログインしているかの確認
        $query = "SELECT `username` 
                    FROM `users` 
                    WHERE id='" . $_COOKIE['userId'] . "'
                      AND token='" . $_COOKIE['token'] . "'";
        $result = mysqli_query($link, $query);

        if ($row = mysqli_fetch_array($result)) {


          //  DBにテキストを追加
          $query = "INSERT 
                      INTO `posts` (`userId`, `username`, `text`, `date`) 
                      VALUES ('" . $_COOKIE['userId'] . "', '" . $row['username'] . "', '" . mysqli_real_escape_string($link, $_POST['text']) . "', NOW())";
          if (mysqli_query($link, $query)) {
            $query = "SELECT * FROM `posts`";
            if (!$posts = mysqli_query($link, $query)) {
              die("テーブルへの接続に失敗しました。\n\n");
            }
            header("Location: index.php");
          } else {
            echo "失敗しました...";
          }
        }
      }
    }
  } elseif ($_POST['postType'] === 'delete') {
    if (array_key_exists('postId', $_POST)) {
      $query = "DELETE
                  FROM `posts` 
                  WHERE `id` = '" . mysqli_real_escape_string($link, $_POST['postId']) . "'
                  LIMIT 1";
      if (mysqli_query($link, $query)) {
        $query = "SELECT * FROM `posts`";
        if (!$posts = mysqli_query($link, $query)) {
          die("テーブルへの接続に失敗しました。\n\n");
        }
        header("Location: index.php");
      } else {
        echo "失敗しました...";
      }
    }
  } elseif ($_POST['postType'] === 'logout') {
    setcookie('token', '', time()-1);
    setcookie('userId', '', time()-1);
    setcookie('username', '', time()-1);
    header("Location: index.php");
  } else {
    echo "Nooooo!!!!!";
  }
} else {
  setToken();
}

?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>study_athena_bbs</title>
  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
  <header>
    <a href="/">
      <h1>
        BBS
      </h1>
    </a>
    <nav>
      <?php if ( isset($_COOKIE['token']) ) : ?>
        <form method="post">
          <input
            type="hidden"
            name="postType"
            value="logout">
          <input
            type="hidden"
            name="token_post"
            value="<?php h($_SESSION['token_post']); ?>">
          <input type="submit" value="Logout">
        </form>
      <?php else : ?>
      <a href="/login.php">
        Login
      </a>
      <a href="/register.php">
        Register
      </a>
      <?php endif; ?>
    </nav>
  </header>
  <main>
    <h2>athena diary</h2>
    <div class="bbs">
      <ul>
        <?php while ($row = mysqli_fetch_array($posts)) : ?>
          <li>
            <article>
              <div class="text">
                <h3>Text</h3>
                <p>
                  <?php echo "${row['text']}"; ?>
                </p>
              </div>
              <div class="username">
                <h3>User</h3>
                <p>
                  <?php echo "${row['username']}"; ?>
                </p>
              </div>
              <div class="date">
                <h3>Date</h3>
                <p>
                  <?php echo "${row['date']}"; ?>
                </p>
              </div>
              <?php if ( isset($_COOKIE['userId']) && $row['userId'] === $_COOKIE['userId'] ) : ?>
                <form method="post">
                  <input
                    type="hidden"
                    name="postType"
                    value="delete">
                  <input
                    type="hidden"
                    name="token_post"
                    value="<?php h($_SESSION['token_post']); ?>">
                  <input
                    type="hidden"
                    name="postId"
                    value=<?php echo $row['id']; ?>>
                  <input type="submit" value="Delete">
                </form>
              <?php endif; ?>
            </article>
          </li>
        <?php endwhile; ?>
      </ul>
      <?php if ( isset($_COOKIE['token']) ) : ?>
        <form method="post">
          <input
            type="hidden"
            name="postType"
            value="new">
          <input
            type="hidden"
            name="token_post"
            value="<?php h($_SESSION['token_post']); ?>">
          <label>
            Text
            <textarea name="text"></textarea>
          </label>
          <input type="submit" value="submit">
        </form>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>





