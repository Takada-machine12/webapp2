<?php
//エラー時の表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('functions.php');
session_start();

//セッションチェック
if (!isset($_SESSION['USER'])) {
    header('Location:'.SITE_URL.'login.php');
    exit;
}

//セッションからユーザー情報を取得
$user = $_SESSION['USER'];
$error = array();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //CSRF対策
    setToken();
} else {
    //CSRF対策
    checkToken();

    $id = $user['id'];
    $delivery_hour = $_POST['delivery_hour'];

    $pdo = connectDb();

    if ($delivery_hour == '') {
        $error['delivery_hour'] = '通知時間を設定してください。';
    }

    if (empty($error)) {
        $sql = "UPDATE user SET delivery_hour = :delivery_hour, updated_at = now() WHERE id = :id ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":delivery_hour" => $delivery_hour, ":id" => $id));

        //セッション上のユーザーデータを更新
        $user['delivery_hour'] = $delivery_hour;
        $_SESSION['USER'] = $user;

        unset($pdo);

        $_SESSION['flash'] = '登録が完了しました。';
        header('Location:'.SITE_URL.'setting.php?');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>設定 | <?php echo SERVICE_NAME; ?></title>
        <meta name="description" content="自分だけの格言をいつも忘れないために。格言リマインダー「マイカクゲン」" />
        <meta name="Keywords" content="マイカクゲン,格言,リマインダー" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/mykakugen.css" rel="stylesheet">
    </head>

    <body id="main">
        <!-- header -->
        <div class="nav navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SERVICE_SHORT_NAME; ?></a>
                    <ul class="nav navbar-nav">
                        <li><a href="./index.php">格言登録</a></li>
                        <li><a href="./item_list.php">格言リスト</a></li>
                        <li class="active"><a href="./setting.php">設定</a></li>
                        <li><a href="./logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <h1>設定</h1>
            <?php if (!empty($_SESSION['flash'])) : ?>
                <div class="alert alert-success" id="flash-msg">
                    <?php echo xss($_SESSION['flash']); ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
                <form method="POST" class="panel panel-default panel-body">
                    
                    <div class="form-group <?php echo !empty($error['delivery_hour']) ? 'has-error' : ''; ?>">
                        <label>メール通知</label>
                        <?php echo arrayToSelect("delivery_hour", $delivery_hours_array, $user['delivery_hour']); ?>
                        <span class="help-block"><?php if (!empty($error['delivery_hour'])) echo xss($error['delivery_hour']); ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-success btn-block" value="登録"/>
                    </div>
                    <input type="hidden" name="token" value="<?php echo xss($_SESSION['sstoken']); ?>" />
                </form>
            <a href="./index.php">戻る</a>
            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
            <script>//指定した時間後に処理を実行
            setTimeout(function() {
                var msg = document.getElementById('flash-msg');
                if (msg) {
                    msg.style.display = 'none';
                }
            }, 1000);
        </script>
        </div>
    </body>
</html>