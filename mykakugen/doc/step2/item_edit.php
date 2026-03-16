<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>格言の編集 | 格言リマインダー マイカクゲン</title>
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
                <a class="navbar-brand" href="#">マイカクゲン</a>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>格言の編集</h1>

        <form method="POST" class="panel panel-default panel-body">
            <div class="form-group">
                <input class="form-control" type="text" name="item_text" value="" />
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="変更">
            </div>
        </form>

        <a href="./item_list.php">戻る</a>

        <hr>
        <footer class="footer">
            <p>&copy; SenseShare</p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>