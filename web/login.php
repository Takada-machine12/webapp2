<?php
//エラー時の表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('functions.php');
session_start();

//Cookieがあれば自動ログイン処理
if (!empty($_COOKIE['MYKAKUGEN'])) {
    $pdo = connectDb();

    //CookieのキーとDBを照合(有効期限も確認)
    $sql = "SELECT * FROM auto_login WHERE c_key = :c_key AND expire > now()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':c_key' => $_COOKIE['MYKAKUGEN']));
    $auto_login_data = $stmt->fetch(); //照合

    if ($auto_login_data) {
        //ユーザー情報を取得してセッションにセット
        $login_user = getUserbyUserId($auto_login_data['user_id'], $pdo);

        $_SESSION['USER'] = $login_user;
        session_regenerate_id(true);
        unset($pdo);
        header('Location:'.SITE_URL.'index.php');
        exit;
    }
    unset($pdo);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //初めて画面にアクセスした時の処理(ユーザーがブラウザでアクセスし、ただ見にきた時。)
} else {
    //フォームからサブミットされた時の処理(入力フォームから「ログイン」ボタンが押された時→フォームを送信した時。)
    //入力されたニックネーム、メールアドレス、パスワードを受け取り、変数に入れる。
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    //エラー定義
    $error = array();

    //[メールアドレス]入力/形式チェック
    if ($user_email == '') {
        $error['user_email'] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error['user_email'] = '正しくないメールアドレスです。';
    }

    //[パスワード]入力チェック
    if ($user_password == '') {
        $error['user_password'] = 'パスワードを入力してください。';
    }

    //入力・形式チェックが問題なければ存在チェック
    if (empty($error)) {
        //データベースに接続する。(PDOを使う。)
        $pdo = connectDb();

        if (!getUser($user_email, $user_password, $pdo)) {
            $error['user_email'] = 'メールアドレスまたはパスワードが間違っています。';
            unset($pdo);
        } else {
            //すべてのエラーがない場合の処理
            //ログインに成功した場合、セッションにユーザ情報を保存する。
            $login_user = getUser($user_email, $user_password, $pdo);
            $_SESSION['USER'] = $login_user;
            $_SESSION['just_logged_in'] = true;

            //自動ログインチェックボックスがONの場合Cookieをクリア
            if (isset($_COOKIE['MYKAKUGEN'])) {
                delete_auto_login($_COOKIE['MYKAKUGEN']);
            }

            //チェックボックスONなら新しくセット
            if (!empty($_POST['auto_login'])) {
                setup_auto_login($login_user['id'], $pdo);
            }

            //ログイン後に毎回セッションIDを書き換える。
            session_regenerate_id(true);

            //HOME画面に遷移
            header('Location: '.SITE_URL.'index.php');
            unset($pdo);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head><!--- 画面のタイトル、文字コードの設定、検索エンジンに引っかけたいキーワードなどを記述 --->
        <meta charset="utf-8">
        <title><?php echo SERVICE_NAME; ?></title>
        <meta name="description" content="自分だけの格言をいつも忘れないために。格言リマインダー「マイカクゲン」" />
        <meta name="Keywords" content="マイカクゲン,格言,リマインダー" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.min.js"></script>
        <link href="css/mykakugen.css" rel="stylesheet">
    </head>

    <body id="main">
        <!-- header -->
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
                        <p>あなたが忘れたくない言葉や格言をマイカクゲンに登録してください。<br />毎日一つ、ランダムにメールでお知らせするシンプルなサービスです。</p>
                        <p><a href="./signup.php" class="btn btn-success btn-lg">新規ユーザー登録（無料） &raquo;</a></p>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title">どんなことに使えるの？</h2>
                                </div>
                                <div class="panel-body">
                                    <p>今日感じた熱い思い、忘れたくない自分の夢、成功体験や失敗体験、教訓、自分のモチベーションが上がる一言、誰かの格言、自分だけの格言、家族や夫婦の決め事、忘れたくない相手への気持ちなど、思いついた時にいつでも登録しておきましょう。もしもあなたが忘れてしまっても、いつかマイカクゲンが思い出させてくれます。</p>
                                </div>
                            </div>
                        </div><!-- col-md-4 -->
                        
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title">お金がかかる？</h2>
                                </div>
                                <div class="panel-body">
                                    <p>全て無料です。</p>
                                </div>
                            </div>
                        </div><!-- col-md-4 -->
                        
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title">登録内容は他人にも見られるの？</h2>
                                </div>
                                <div class="panel-body">
                                    <p>登録した内容は自分のみ見ることができます。</p>
                                </div>
                            </div>
                        </div><!-- col-md-4 -->
                    </div><!-- row -->
                </div><!-- col-md-9 -->
                
                <div class="col-md-3">
                    <div class="sidebar-nav panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">ログイン</h2>
                        </div>
                        <div class="panel-body">
                            <form method="POST">
                                <div class="form-group <?php echo !empty($error['user_email']) ? "has-error" : ''; ?>">
                                    <label>メールアドレス</label>
                                    <input type="text" name="user_email" style="width: 100%;" class="form-control" placeholder="メールアドレス" value="<?php echo xss($user_email ??''); ?>">
                                    <span class="help-block"><?php if (!empty($error['user_email'])) echo xss($error['user_email']); ?></span>
                                </div>

                                <div class="form-group <?php echo !empty($error['user_password']) ? "has-error" : ''; ?>">
                                    <label>パスワード</label>
                                    <input type="password" name="user_password" style="width: 100%;" class="form-control" placeholder="パスワード" value="">
                                    <span class="help-block"><?php if (!empty($error['user_password'])) echo xss($error['user_password']); ?></span>
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-block" value="ログイン">
                                </div>
                                
                                <div class="form-group">
                                    <label style="font-size: 14zpx;">
                                        <input type="checkbox" name="auto_login" value="1">次回から自動でログイン
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- col-md-3 -->
            </div><!-- row -->
            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
        </div><!-- container -->
    </body>
</html>