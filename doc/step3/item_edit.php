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

$pdo = connectDb();

// パラメータで渡されたアイテムIDを取得
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 初めて画面にアクセスした時の処理

    $sql = "select * from item where id = :id limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":id" => $id));
    $item = $stmt->fetch();

    $item_text = $item['item_text'];
} else {
    // フォームからサブミットされた時の処理

    $item_text = $_POST['item_text'];

    $err = array();
    $complete_msg = "";

    // 格言が空
    if ($item_text == '') {
        $err['item_text'] = '格言を入力して下さい。';
    }

    if (empty($err)) {
        // 格言登録処理
        $sql = "update item
				set
				item_text = :item_text,
				updated_at = now()
				where
				id = :id";
        $stmt = $pdo->prepare($sql);
        $params = array(
            ":item_text" => $item_text,
            ":id" => $id
        );
        $stmt->execute($params);

        $complete_msg = "格言が登録されました。";
    }
}
unset($pdo);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>格言の編集 | <?php echo SERVICE_NAME; ?></title>
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
                    <li class="active"><a href="./item_list.php">格言リスト</a></li>
                    <li><a href="./setting.php">設定</a></li>
                    <li><a href="./logout.php">ログアウト</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>格言の編集</h1>

        <?php if ($complete_msg) : ?>
            <div class="alert alert-success">
                <?php echo $complete_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="panel panel-default panel-body">
            <div class="form-group">
                <input class="form-control" type="text" name="item_text" value="<?php echo $item_text; ?>" /><span class="help-inline"><?php echo $err['item_text']; ?></span>
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="変更">
            </div>
        </form>

        <a href="./item_list.php">戻る</a>

        <hr>
        <footer class="footer">
            <p><?php echo COPYRIGHT; ?></p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>