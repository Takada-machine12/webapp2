<?php
require_once('config.php');
require_once('functions.php');
session_start();

//セッションチェック
if (!isset($_SESSION['USER'])) {
    header('Location.'.SITE_URL.'login.php');
    exit;
}
//セッションからユーザー情報を取得
$user = $_SESSION['USER'];

//アイテムを削除
$id = $_GET['id'];

$pdo = connectDb();
$sql = "DELETE FROM item WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $id));
unset($pdo);

header('Location:'.SITE_URL.'item_list.php');
exit;
?>