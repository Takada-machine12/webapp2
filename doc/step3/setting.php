<?php
require_once('config.php');
require_once('functions.php');

session_start();

// ログインチェック
if (!isset($_SESSION['USER'])) {
    header('Location: ' . SITE_URL . 'login.php');
    exit;
}

// セッションからユーザ情報を取得
$user = $_SESSION['USER'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
} else {
    $delivery_hour = $_POST['delivery_hour'];

    $pdo = connectDb();

    $err = array();
    $complete_msg = "";

    // メール通知時間が空
    if ($delivery_hour == '') {
        $err['delivery_hour'] = 'メール通知時間を指定して下さい。';
    }

    if (empty($err)) {
        // 更新処理
        $sql = "update user
				set
				delivery_hour = :delivery_hour,
				updated_at = now()
				where
				id = :id";
        $stmt = $pdo->prepare($sql);
        $params = array(
            ":delivery_hour" => $delivery_hour,
            ":id" => $user['id']
        );
        $stmt->execute($params);

        // セッション上のユーザデータを更新
        $user['delivery_hour'] = $delivery_hour;
        $_SESSION['USER'] = $user;

        $complete_msg = "変更されました。";
    }
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>設定 | <?php echo SERVICE_NAME; ?></title>
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
        <?php if ($complete_msg) : ?>
            <div class="alert alert-success">
                <?php echo $complete_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="panel panel-default panel-body">

            <div class="form-group <?php if ($err['delivery_hour'] != '') echo 'has-error'; ?>">
                <label>メール通知</label>
                <?php echo arrayToSelect("delivery_hour", $delivery_hours_array, $user['delivery_hour']); ?>
                <span class="help-block"><?php echo $err['delivery_hour']; ?></span>
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="登録">
            </div>
        </form>

        <a href="./index.php">戻る</a>

        <hr>
        <footer class="footer">
            <p><?php echo COPYRIGHT; ?></p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>