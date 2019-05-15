<?php

include_once './class/start.php';

////////////////////////////////////////
//
//  フォームが入力されているか確認
//
////////////////////////////////////////

if (array_key_exists('username', $_POST) && array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {

  // データが入力されているかを確認する
  if ($_POST['email'] === '') {
    echo "emailが入力されていません！";
  } elseif ($_POST['password'] === '') {
    echo "passwordが入力されていません！";
  } else {


    // メールアドレスがすでに使用されていないかチェックする
    $query = "SELECT `id` 
                FROM `users` 
                WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'";
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result)) {
      echo "すでにそのemailは使用されています";
    } else {


      // passwordを暗号化する
      $pw = md5(md5($_POST['email']).$_POST['password']);


      // tokenを発行する
      $token = md5(md5($_POST['email']).date("DdMYHis"));


      // 重複がなければユーザー登録（データベーステーブルに追加する）を実行する
      $query = "INSERT 
                  INTO `users` (`token`, `username`, `email`, `password`) 
                  VALUES ('".mysqli_real_escape_string($link, $token)."', '".mysqli_real_escape_string($link, $_POST['username'])."', '".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $pw)."')";
      if (mysqli_query($link, $query)) {

        $query = "SELECT `id` 
                    FROM `users` 
                    WHERE token='".$token."'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);

        // cookieの設定
        setcookie('token', $token, time()+60*60*24);
        setcookie('userId', $row['id'], time()+60*60*24*30);
        setcookie('username', mysqli_real_escape_string($link, $_POST['username']), time()+60*60*24*30);


        // index.phpに遷移する
        header("Location: index.php");
      } else {
        echo "登録に失敗しました！";
      }
    }
  }
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
      <a href="/index.php">
        Top
      </a>
    </nav>
  </header>
  <main>
    <h2>Register</h2>
    <form method="post">
      <label>
        username
        <input name="username" type="text" placeholder="username" required>
      </label>
      <label>
        email
        <input name="email" type="email" placeholder="email" required>
      </label>
      <label>
        password
        <input name="password" type="password" placeholder="password" required>
      </label>
      <input type="submit" value="submit">
    </form>
    <a href="login.php">
      go login!
    </a>
  </main>
</body>
</html>



