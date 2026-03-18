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

//DB接続
$pdo = connectDb();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //CSRF対策
    checkToken();

    $id = $_POST['id'];
    $item_text = $_POST['item_text'];

    $sql = "UPDATE item SET item_text = :item_text, updated_at = now() WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":item_text" => $item_text, ":id" => $id, ":user_id" => $_SESSION['USER']['id']));
    unset($pdo);

    $_SESSION['flash'] = '変更が完了しました。続けて変更する場合は下に入力してください。';
    header('Location:'.SITE_URL.'item_edit.php?id='.$id);
    exit;
} elseif (isset($_GET['id'])) { //issetで存在チェックをしてからアクセス
    //CSRF対策
    setToken();

    $id = $_GET['id'];

    $sql = "SELECT * FROM item WHERE id = :id AND user_id = :user_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":id" => $id, ":user_id" => $_SESSION['USER']['id']));

    $item = $stmt->fetch();

    unset($pdo);

    //存在しないIDや他ユーザーのIDにアクセスした場合の処理
    if (!$item) {
        header('Location:'.SITE_URL.'item_list.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>格言の編集 | <?php echo SERVICE_NAME; ?></title>
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
                        <li><a href="./setting.php">設定</a></li>
                        <li><a href="./logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <h1>格言の編集</h1>
            <?php if (!empty($_SESSION['flash'])) : ?>
                <div class="alert alert-success">
                    <?php echo xss($_SESSION['flash']); ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            <form method="POST" class="panel panel-default panel-body">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                
                <div class="form-group">
                    <label>格言</label>
                    <input type="text" name="item_text" class="form-control" value="<?php echo xss($item['item_text']); ?>" />
                    <span class="help-block"></span>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success btn-block" value="変更"/>
                </div>
                <input type="hidden" name="token" value="<?php echo xss($_SESSION['sstoken']); ?>" />

            </form>
            <a href="./item_list.php">戻る</a>
            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
        </div>
    </body>
</html>