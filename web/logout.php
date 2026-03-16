<?php
//エラー時の表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('functions.php');
session_start();

//オートログインのCookieがあれば削除
if (isset($_COOKIE['MYKAKUGEN'])) {
    $auto_login_key = $_COOKIE['MYKAKUGEN'];

    //Cookieを削除
    delete_auto_login($_COOKIE['MYKAKUGEN']);
}

//セッション内のデータ削除
$_SESSION = array();

//クッキーの無効化
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-86400, '/');
}

//セッションの破棄
session_destroy();

header('Location:'.SITE_URL.'login.php');
?>