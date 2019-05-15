<?php
  $host = "localhost";
  $user = "root";
  $password = "root";
  $db = "study_athena_bbs";

  //  infos
  $mysql_version = "8.0.15";
  $user_plugin = "mysql_native_password";
  $db_name = "study_athena_bbs";
  $db_tables = [
    "users" => [
      "id",
      "token",
      "username",
      "email",
      "password",
    ],
    "posts" => [
      "id",
      "userId",
      "username",
      "text",
      "date",
    ],
  ];
