<?php
require_once('config.php');
require_once('functions.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 初めて画面にアクセスした時の処理
} else {
    // フォームからサブミットされた時の処理

    // 入力されたニックネーム、メールアドレス、パスワードを受け取り、変数に入れる。
    $user_screen_name = $_POST['user_screen_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    $pdo = connectDb();

    // 入力チェックを行う。
    $err = array();

    // [ニックネーム]未入力チェック
    if ($user_screen_name == '') {
        $err['user_screen_name'] = 'ニックネームを入力して下さい。';
    }

    if ($user_email == '') {
        // [メールアドレス]未入力チェック
        $err['user_email'] = 'メールアドレスを入力して下さい。';
    } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        // [メールアドレス]形式チェック
        $err['user_email'] = 'メールアドレスが不正です。';
    } else {
        // [メールアドレス]存在チェック
        if (checkEmail($user_email, $pdo)) {
            $err['user_email'] = 'このメールアドレスは既に登録されています。';
        }
    }

    // [パスワード]未入力チェック
    if ($user_password == '') {
        $err['user_password'] = 'パスワードを入力して下さい。';
    }

    // もし$err配列に何もエラーメッセージが保存されていなかったら
    if (empty($err)) {
        // データベース（userテーブル）に新規登録する。
        $sql = "insert into user
            	(user_screen_name, user_password, user_email, delivery_hour, created_at, updated_at)
                values
                (:user_screen_name, :user_password, :user_email, 99, now(), now())";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_screen_name', $user_screen_name);
        $stmt->bindValue(':user_password', $user_password);
        $stmt->bindValue(':user_email', $user_email);

        $flag = $stmt->execute();

        // 自動ログイン
        $user = getUser($user_email, $user_password, $pdo);
        $_SESSION['USER'] = $user;

        // 管理者にメール送信
        mb_language("japanese");
        mb_internal_encoding("UTF-8");

        $mail_title = '【マイカクゲン】新規ユーザ登録がありました。';
        $mail_body = 'ニックネーム：' . $user['user_screen_name'] . PHP_EOL;
        $mail_body .= 'メールアドレス：' . $user['user_email'];

        mb_send_mail(ADMIN_MAIL_ADDRESS, $mail_title, $mail_body);

        // signup_complete.phpに画面遷移する。
        header('Location: ' . SITE_URL . 'signup_complete.php');

        unset($pdo);
        exit;
    }
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>ユーザー登録 | <?php echo SERVICE_NAME; ?></title>
    <meta name="description" content="自分だけの格言をいつも忘れないために。格言リマインダー「マイカクゲン」" />
    <meta name="keywords" content="マイカクゲン,格言,リマインダー" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <link href="css/mykakugen.css" rel="stylesheet">
</head>

<body id="main">
    <div class="nav navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SERVICE_SHORT_NAME; ?></a>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>ユーザー登録</h1>

        <form method="POST" class="panel panel-default panel-body">

            <div class="form-group <?php if ($err['user_screen_name'] != '') echo 'has-error'; ?>">
                <input class="form-control" type="text" name="user_screen_name" value="<?php echo $user_screen_name ?>" placeholder="ニックネーム" /><span class="help-block"><?php echo $err['user_screen_name']; ?></span>
            </div>

            <div class="form-group <?php if ($err['user_password'] != '') echo 'has-error'; ?>">
                <input class="form-control" type="password" name="user_password" placeholder="パスワード" /><span class="help-block"><?php echo $err['user_password']; ?></span>
            </div>

            <div class="form-group <?php if ($err['user_email'] != '') echo 'has-error'; ?>">
                <input class="form-control" type="text" name="user_email" value="<?php echo $user_email; ?>" placeholder="メールアドレス" /><span class="help-block"><?php echo $err['user_email']; ?></span>
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="アカウントを作成">
            </div>
        </form>

        <hr>
        <footer class="footer">
            <p><?php echo COPYRIGHT; ?></p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>