<?php
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
$id = $user['id'];
$items = array();

//ログイン中のユーザーの格言を取得
$pdo = connectDb();
$sql = "SELECT * FROM item WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":user_id" => $id));
$count = $stmt->rowCount(); //件数取得

unset($pdo);

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>あなたが登録した格言 | <?php echo SERVICE_NAME; ?></title>
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
                        <li class="active"><a href="./item_list.php">格言リスト</a></li>
                        <li><a href="./setting.php">設定</a></li>
                        <li><a href="./logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <h1>あなたが登録した格言</h1>
            <?php if ($count == 0): ?>
                <div class="alert" id="message">格言が登録されていません。</div>
            <?php endif; ?>
                <ul class="list-group">
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?><!--　item_textをDBから一個ずつ取り出し、<li>以降の処理を実行する。 -->
                        <li class="list-group-item">
                            <?php echo xss($row['item_text']); ?>
                            <a href="./item_edit.php?id=<?php echo $row['id']; ?>">[編集]</a>
                            <a href="#" class="link-delete" onclick="if(confirm('本当に削除しますか？')){ location.href='./item_delete.php?id=<?php echo $row['id']; ?>'}">[削除]</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <a href="./index.php">戻る</a>
            <hr>
            <footer class="footer">
                <p><?php echo COPYRIGHT; ?></p>
            </footer>
        </div>
    </body>
</html>