<?php
//データベースに接続する。
function connectDb() {
    $param = 'mysql:dbname='.DB_NAME.';host='.DB_HOST;
    try {
        $pdo = new PDO($param, DB_USER, DB_PASSWORD); //DB接続
        $pdo->query('SET NAMES utf8'); //文字コード指定

        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();

        exit;
    }
}

//メールアドレスの存在チェック
function checkEmail($user_email, $pdo) {
    $sql = "SELECT * FROM user WHERE user_email = :user_email limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" => $user_email));
    $first_user = $stmt->fetch();

    return $first_user ? true : false;
}

//メールアドレスとパスワードからuserを検索する
function getUser($user_email, $user_password, $pdo) {
    $sql = "SELECT * FROM user WHERE user_email = :user_email AND BINARY user_password = :user_password LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_email" => $user_email, ":user_password" => $user_password));
    $login_user = $stmt->fetch();

    return $login_user ? $login_user : false;
}

//config.phpで定義した配列(通知時間)からプルダウンメニューを生成する
function arrayToselect($inputName, $srcArray, $selectedIndex = "") {
    $temphtml = '<select class="form-control" name="'.$inputName.'">'.PHP_EOL;

    foreach ($srcArray as $key => $val) {
        if ($selectedIndex == $key) {
            $selectedText = 'selected="selected"';
        } else {
            $selectedText = '';
        }
        $temphtml .= '<option value="'.$key.'"'.$selectedText.'>'.$val.'</option>'.PHP_EOL;
    }
    $temphtml .= '</select>'.PHP_EOL;
    return $temphtml;
}

//XSS対策
function xss($original_str) {
    return htmlspecialchars($original_str, ENT_QUOTES, "UTF-8");
}

//トークンを発行する処理
function setToken() {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['sstoken'] = $token;
}

//トークンをチェックする処理
function checkToken() {
    if (empty($_SESSION['sstoken']) || ($_SESSION['sstoken'] != $_POST['token'])) {
        echo '<html><head><meta charset="utf-8"></head><body>不正なアクセスです。</body></html>';

        exit;
    }
}

//オートログイン セットアップ
function setup_auto_login($user_id, $pdo) {
    //ランダムなキーを生成(トークンと同じ)
    $c_key = sha1(uniqid(mt_rand(), true));

    //有効期限を1年後に設定
    $expire = date('Y-m-d H:i:s', time()+3600*24*365);

    //DBに保存
    $sql = "INSERT INTO auto_login (user_id, c_key, expire, created_at, updated_at)
            VALUES (:user_id, :c_key, :expire, now(), now())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':user_id' => $user_id, ':c_key' => $c_key, ':expire' => $expire));

    //ブラウザのCookieにも同じキーを保存
    setcookie('MYKAKUGEN', $c_key, time()+3600*24*365, '/');
}

//オートログイン デリート
function delete_auto_login($c_key) {
    //DBから削除
    $pdo = connectDb();
    $sql = "DELETE FROM auto_login WHERE c_key = :c_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':c_key' => $c_key));
    unset($pdo);

    //Cookieを削除(有効期限を過去にすることで削除扱いになる。)
    setcookie('MYKAKUGEN', '', time()-86400, '/develop/mykakugen/web/');
}

//ユーザーIDからuserを検索
function getUserbyUserId($user_id, $pdo) {
    $sql = "SELECT * FROM user WHERE id = :user_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":user_id" => $user_id));
    $user = $stmt->fetch();

    return $user ? $user : false;
}
?>
