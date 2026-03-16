<?php

$host = "localhost";

$user = "root";

$pass = "";

$db = "contact";



$conn = mysqli_connect($host, $user, $pass) or die('host接続失敗');

mysqli_select_db($conn, $db) or die('db接続失敗');



mysqli_set_charset($conn, 'utf8');



$sql = "INSERT INTO contact (name, kana, mail, address, tel, comment) VALUES ('".$_POST['name']."','".$_POST['kana']."','".$_POST['mail']."','".$_POST['address']."','".$_POST['tel']."','".$_POST['comment']."')";

$result_flag = mysqli_query($conn, $sql);



if (!$result_flag) {

    die('INSERT失敗'.mysqli_error($conn));

}



mysqli_close($conn) or die('MySQL切断に失敗しました。');



mb_language('japanese');

mb_internal_encoding('UTF-8');



$body = "氏名：" . $_POST['name'] . PHP_EOL . "お問い合わせ内容：" . $_POST['comment'];

mb_send_mail($_POST['mail'], 'お問い合わせありがとうございました', $body);



?>

<html>

<head>

<title>お問い合わせフォーム</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>

<body>

<p>メールが送信されました。</p>

</body>

</html>