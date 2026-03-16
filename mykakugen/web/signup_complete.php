<?php
//エラー時の表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>登録完了 | <?php echo SERVICE_NAME; ?></title>
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
            <div class="panel-heading">
                <h2 class="panel-title">ユーザー登録完了</h2>
            </div>
            <div class="alert alert-success">
                登録が完了しました。
            </div>

            <a href="./index.php">戻る</a>

            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
        </div>
    </body>
</html>