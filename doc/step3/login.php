<?php

require_once('config.php');
require_once('functions.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 初めて画面にアクセスした時の処理
} else {
    // フォームからサブミットされた時の処理

    // 入力されたメールアドレス、パスワードを受け取り、変数に入れる。
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // データベースに接続する（PDOを使う）
    $pdo = connectDb();

    // 入力チェックを行う。
    $err = array();

    if ($user_email == '') {
        // [メールアドレス]未入力チェック
        $err['user_email'] = 'メールアドレスを入力して下さい。';
    } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        // [メールアドレス]形式チェック
        $err['user_email'] = 'メールアドレスが不正です。';
    } else {
        // [メールアドレス]存在チェック
        if (!checkEmail($user_email, $pdo)) {
            $err['user_email'] = 'このメールアドレスが登録されていません。';
        }
    }

    // [パスワード]未入力チェック
    if ($user_password == '') {
        $err['user_password'] = 'パスワードを入力して下さい。';
    }

    if ($user_email && $user_password) {
        // メールアドレスとパスワードが正しくない
        $user = getUser($user_email, $user_password, $pdo);
        if (!$user) {
            $err['user_password'] = 'パスワードが正しくありません。';
        }
    }

    // もし$err配列に何もエラーメッセージが保存されていなかったら
    if (empty($err)) {
        // ログインに成功したのでセッションにユーザデータを保存する。
        $_SESSION['USER'] = $user;

        // HOME画面に遷移する。
        header('Location:' . SITE_URL . 'index.php');
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
    <title><?php echo SERVICE_NAME; ?></title>
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

        <div class="row">

            <div class="col-md-9">
                <div class="jumbotron">

                    <h1>大切なことを忘れないために。</h1>
                    <p>あなたが忘れたくない言葉や格言をマイカクゲンに登録して下さい。<br />毎日一つ、ランダムにメールでお知らせするシンプルなサービスです。</p>
                    <p><a href="./signup.php" class="btn btn-success btn-lg">新規ユーザー登録（無料） &raquo;</a></p>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">どんなことに使えるの？</h2>
                            </div>
                            <div class="panel-body">
                                <p>今日感じた熱い思い、忘れたくない自分の夢、成功体験や失敗体験、教訓、自分のモチベーションが上がる一言、誰かの格言、自分だけの格言、家族や夫婦の決め事、忘れたくない相手への気持ちなど、思いついたときにいつでも登録しておきましょう。もしもあなたが忘れてしまっても、いつかマイカクゲンが思い出させてくれます。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">お金がかかる？</h2>
                            </div>
                            <div class="panel-body">
                                <p>全て無料です。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">登録内容は他人にも見られるの？</h2>
                            </div>
                            <div class="panel-body">
                                <p>登録した内容は自分のみ見ることができます。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                </div>
                <!--/row-->

            </div>
            <!--/col-md-9-->

            <div class="col-md-3">
                <div class="sidebar-nav panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">ログイン</h2>
                    </div>
                    <div class="panel-body">
                        <form method="POST">
                            <div class="form-group <?php if ($err['user_email'] != '') echo 'has-error'; ?>">
                                <label>メールアドレス</label>
                                <input type="text" class="form-control" name="user_email" value="<?php echo $user_email; ?>" placeholder="メールアドレス" /><span class="help-block"><?php echo $err['user_email']; ?></span>
                            </div>

                            <div class="form-group <?php if ($err['user_password'] != '') echo 'has-error'; ?>">
                                <label>パスワード</label>
                                <input type="password" class="form-control" name="user_password" placeholder="パスワード" /><span class="help-block"><?php echo $err['user_password']; ?></span>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="ログイン" class="btn btn-primary btn-block">
                            </div>

                            <div class="form-group">
                                <input type="checkbox">次回から自動でログイン
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/col-md-3-->

        </div>
        <!--/row-->

        <hr>
        <footer class="footer">
            <p><?php echo COPYRIGHT; ?></p>
        </footer>

    </div>
    <!--/.container-->

</body>

</html>