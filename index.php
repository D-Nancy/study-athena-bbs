<?php

// sessionをスタートさせる
session_start();

// ログインをしていなければ（cookieにtokenが設定されていない）login.phpに遷移する
if (!$_COOKIE['token']) {
  header("Location: login.php");
}

echo $_COOKIE['username'];

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
