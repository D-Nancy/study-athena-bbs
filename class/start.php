<?php
  session_start();

  require_once('./config/server.php');

  $link = mysqli_connect($host, $user, $password, $db);

  if (mysqli_connect_error()) {
    die("データベースへの接続に失敗しました。\n\n");
  }
