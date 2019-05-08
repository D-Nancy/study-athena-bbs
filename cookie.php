<?php

////////////////////////////////////////
//
// cookieについて
//
////////////////////////////////////////

// cookieにデータを保存する
/*
setcookie('userid', '123', time()+60*60*24);
echo $_COOKIE['userid']."\n\n";
*/


// cookieを無効にする
setcookie('userid', '', time()-60*60*24);
