<?php

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
//  ユーザーのデータを挿入： INSERT
//
////////////////////////////////////////

/*
$query = "INSERT INTO `users` (`email`, `password`) VALUES ('test3@test.com', 'Password3')";
if ($result = mysqli_query($link, $query)) {
  echo "INSERTクエリの発行に成功しました\n\n";
}
*/




////////////////////////////////////////
//
//  ユーザーのデータを更新： UPDATE
//
////////////////////////////////////////

/*
$query = "UPDATE `users` SET password='Password10' WHERE email='test10@test.com' LIMIT 1";
if ($result = mysqli_query($link, $query)) {
  echo "UPDATEクエリの発行に成功しました\n\n";
}
*/




////////////////////////////////////////
//
//  ユーザーのデータを所得： SELECT
//
////////////////////////////////////////

  // 全てのユーザーのデータ
  $query = "SELECT * FROM `users`";


  // 指定のユーザーのみ
//  $query = "SELECT * FROM `users` WHERE id='1'";
//  $query = "SELECT * FROM `users` WHERE email LIKE '''";


  // 特殊文字が含まれているかもしれないデータ
//  $name = "Test'3 Test3";
//  $query = "SELECT * FROM `users` WHERE name='".mysqli_real_escape_string($link, $name)."'";


  if ($result = mysqli_query($link, $query)) {
    echo "SELECTクエリの発行に成功しました\n\n";
  }




////////////////////////////////////////
//
//  値を表示
//
////////////////////////////////////////

  while ($row = mysqli_fetch_array($result)) {
    echo "ID: ${row['id']}\n";
    echo "username: ${row['username']}\n";
    echo "mail: ${row['email']}\n";
    echo "pw: ${row['password']}\n\n";
  }



