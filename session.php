<?php

////////////////////////////////////////
//
// sessionについて
//
////////////////////////////////////////

// sessionをスタートさせる
session_start();

// ログインをしていなければ（sessionにemailが設定されていない）register.phpに遷移する
if ($_SESSION['email']) {
  echo "ログインしています。";
} else {
  header("Location: register.php");
}
