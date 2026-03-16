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

//セッション情報を取得
$user = $_SESSION['USER'];


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //CSRF対策
    setToken();
} else {
    //CSRF対策
    checkToken();

    //格言を登録
    $user_id = $user['id']; //セッションからuser_idを取得
    $item_text = $_POST['item_text'];

    $error = array();

    if ($item_text == '') {
        $error['item_text'] = '格言を入力してください。';
    } else {
        $pdo = connectDb();

        $sql = "INSERT INTO item (user_id, item_text, created_at, updated_at)
                VALUES (:user_id, :item_text, now(), now())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":user_id" => $user_id, ":item_text" => $item_text));

        unset($pdo);

        $_SESSION['flash'] = '登録されました。続けて登録する場合は下に入力してください。';
        header('Location:'.SITE_URL.'index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>HOME | <?php echo SERVICE_NAME; ?></title>
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
            <?php if (!empty($_SESSION['just_logged_in'])) : ?>
                <div id="welcome-msg" class="alert alert-success">
                    こんにちは、<?php echo xss($_SESSION['USER']['user_screen_name']); ?>さん！
                </div>
                <?php unset($_SESSION['just_logged_in']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash'])) : ?>
                <div class="alert alert-success">
                    <?php echo xss($_SESSION['flash']); ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
                <form method="POST" class="panel panel-default panel-body">
                    
                    <div class="form-group <?php echo !empty($error['item_text']) ? "has-error" : ''; ?>">
                        <label>あなたの格言を登録してください。</label><br />
                        <input type="text" name="item_text" class="form-control"  placeholder="格言を入力" style="width: 100%;" value="" />
                        <span class="help-block"><?php if (!empty($error['item_text'])) echo xss($error['item_text']); ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-success btn-block" value="登録"/>
                    </div>
                    <input type="hidden" name="token" value="<?php echo xss($_SESSION['sstoken']); ?>" />

                </form>
            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
        </div><!-- container -->
        <script>//指定した時間後に処理を実行
            setTimeout(function() {
                var msg = document.getElementById('welcome-msg');
                if (msg) {
                    msg.style.display = 'none';
                }
            }, 1000);
        </script>
    </body>
</html>