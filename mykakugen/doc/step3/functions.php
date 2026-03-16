<?php

// データベースに接続する
function connectDb()
{
    $param = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;

    $pdo = new PDO($param, DB_USER, DB_PASSWORD);
    $pdo->query('SET NAMES utf8;');

    return $pdo;
}

// メールアドレスの存在チェック
function checkEmail($user_email, $pdo)
{
    $sql = "select * from user where user_email = :user_email limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" => $user_email));
    $user = $stmt->fetch();

    return $user ? true : false;
}

// メールアドレスとパスワードからuserを検索する
function getUser($user_email, $user_password, $pdo)
{
    $sql = "select * from user where user_email = :user_email and BINARY user_password = :user_password limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" => $user_email, ":user_password" => $user_password));
    $user = $stmt->fetch();

    return $user ? $user : false;
}

// 配列からプルダウンメニューを生成する
function arrayToSelect($inputName, $srcArray, $selectedIndex = "")
{
    $temphtml = '<select class="form-control" name="' . $inputName . '">' . "\n";

    foreach ($srcArray as $key => $val) {
        if ($selectedIndex == $key) {
            $selectedText = ' selected="selected"';
        } else {
            $selectedText = '';
        }
        $temphtml .= '<option value="' . $key . '"' . $selectedText . '>' . $val . '</option>' . "\n";
    }

    $temphtml .= '</select>' . "\n";
    return $temphtml;
}
