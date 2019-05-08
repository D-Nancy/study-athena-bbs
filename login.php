<?php

// ログインをしていれば（cookieにtokenが設定されている）index.phpに遷移する
  if (isset($_COOKIE['token'])) {
    header("Location: index.php");
  }

////////////////////////////////////////////////////////////////////////////////
//
//  サーバーへの接続
//
//  MySQL version: 8.0.15
//  User Plugin: mysql_native_password
//  Dasabase
//    Name: study_athena_bbs
//    Tables: users（ユーザー情報を載せている）
//
////////////////////////////////////////////////////////////////////////////////

  $link = mysqli_connect("localhost", "root", "root", "study_athena_bbs");

  if (mysqli_connect_error()) {
    die("データベースへの接続に失敗しました。\n\n");
  }




////////////////////////////////////////
//
//  フォームが入力されているか確認
//
////////////////////////////////////////

  if (array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {

    // データが入力されているかを確認する
    if ($_POST['email'] === '') {
      echo "emailが入力されていません！";
    } elseif ($_POST['password'] === '') {
      echo "passwordが入力されていません！";
    } else {


      // passwordを暗号化する
      $pw = md5(md5($_POST['email']).$_POST['password']);

      // ユーザーがあるかどうかチェックする
      $query = "SELECT id
            FROM `users` 
            WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'
                AND password='".mysqli_real_escape_string($link, $pw)."'";
      $result = mysqli_query($link, $query);

      if ($row = mysqli_fetch_array($result)) {


        // tokenを発行する
        $token = md5(md5($_POST['email']).date("DdMYHis"));


        // ユーザーがあればユーザーログイン（tokenをcookieにセット）を実行する
        $query = "UPDATE `users` 
            SET token='".mysqli_real_escape_string($link, $token)."'
            WHERE id='".mysqli_real_escape_string($link, $row['id'])."'
            LIMIT 1";
        if (mysqli_query($link, $query)) {


          // cookieの設定
          setcookie('token', $token, time()+60*60*24);
          setcookie('email', mysqli_real_escape_string($link, $_POST['email']), time()+60*60*24*30);


          // index.phpに遷移する
          header("Location: index.php");
        } else {
          echo "ログインに失敗しました！";
        }
      } else {
        echo "そのユーザーは使用されていません";
      }
    }
  }
?>




<!--
////////////////////////////////////////////////////////////////////////////////
//
//  フォームの実装
//
//  # フォーム送信の流れ
//  1. Eメールとパスワードの入力フォーム、「submit」ボタンを設定する
//  2. データが入力されているかを確認する
//  3. ユーザーがあるかどうかチェックする
//  4. ユーザーがあればユーザーログイン（tokenをcookieにセット）を実行する
//
////////////////////////////////////////////////////////////////////////////////
-->

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
        掲示板
      </h1>
    </a>
  </header>
  <main>
    <h2>Login</h2>
    <form method="post">
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
    <a href="register.php">
      go register!
    </a>
  </main>
</body>
</html>




