<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>ユーザー登録 | 格言リマインダー マイカクゲン</title>
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

        <h1>ユーザー登録</h1>

        <form method="POST" class="panel panel-default panel-body" action="./signup_complete.php">

            <div class="form-group">
                <label>ニックネーム</label>
                <input class="form-control" type="text" name="user_screen_name" value="" />
            </div>

            <div class="form-group">
                <label>パスワード</label>
                <input class="form-control" type="password" name="user_password" value="" />
            </div>

            <div class="form-group">
                <label>メールアドレス</label>
                <input class="form-control" type="text" name="user_email" value="" />
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="アカウントを作成">
            </div>
        </form>

        <hr>
        <footer class="footer">
            <p>&copy; SenseShare</p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>