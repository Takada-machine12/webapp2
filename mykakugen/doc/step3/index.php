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
    $item_text = $_POST['item_text'];

    $pdo = connectDb();

    $err = array();
    $complete_msg = "";

    // 格言が空
    if ($item_text == '') {
        $err['item_text'] = '格言を入力して下さい。';
    }

    if (empty($err)) {
        // 格言登録処理
        $sql = "insert into item
				(item_text, user_id, created_at, updated_at)
				values
				(:item_text, :user_id, now(), now())";
        $stmt = $pdo->prepare($sql);
        $params = array(
            ":item_text" => $item_text,
            ":user_id" => $user['id']
        );
        $stmt->execute($params);

        $complete_msg = "登録されました。<br />続けて登録する場合は下に入力してください。";
        $item_text = "";
    }
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>HOME | <?php echo SERVICE_NAME; ?></title>
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
                    <li class="active"><a href="./index.php">格言登録</a></li>
                    <li><a href="./item_list.php">格言リスト</a></li>
                    <li><a href="./setting.php">設定</a></li>
                    <li><a href="./logout.php">ログアウト</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>HOME</h1>

        <?php if ($complete_msg) : ?>
            <div class="alert alert-success">
                <?php echo $complete_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="panel panel-default panel-body">

            <div class="form-group <?php if ($err['item_text'] != '') echo 'has-error'; ?>">
                <label>あなたの格言を登録して下さい。</label>
                <input class="form-control" type="text" name="item_text" value="<?php echo $item_text; ?>" placeholder="格言を入力" /><span class="help-block"><?php echo $err['item_text']; ?></span>
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="登録">
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