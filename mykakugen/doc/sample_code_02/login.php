<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>格言リマインダー マイカクゲン</title>
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

        <div class="row">

            <div class="col-md-9">
                <div class="jumbotron">

                    <h1>大切なことを忘れないために。</h1>
                    <p>あなたが忘れたくない言葉や格言をマイカクゲンに登録して下さい。<br />毎日一つ、ランダムにメールでお知らせするシンプルなサービスです。</p>
                    <p><a href="./signup.php" class="btn btn-success btn-lg">新規ユーザー登録（無料） &raquo;</a></p>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">どんなことに使えるの？</h2>
                            </div>
                            <div class="panel-body">
                                <p>今日感じた熱い思い、忘れたくない自分の夢、成功体験や失敗体験、教訓、自分のモチベーションが上がる一言、誰かの格言、自分だけの格言、家族や夫婦の決め事、忘れたくない相手への気持ちなど、思いついたときにいつでも登録しておきましょう。もしもあなたが忘れてしまっても、いつかマイカクゲンが思い出させてくれます。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">お金がかかる？</h2>
                            </div>
                            <div class="panel-body">
                                <p>全て無料です。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">登録内容は他人にも見られるの？</h2>
                            </div>
                            <div class="panel-body">
                                <p>登録した内容は自分のみ見ることができます。</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-md-4-->

                </div>
                <!--/row-->

            </div>
            <!--/col-md-9-->

            <div class="col-md-3">
                <div class="sidebar-nav panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">ログイン</h2>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="./index.php">
                            <div class="form-group">
                                <label>メールアドレス</label>
                                <input type="text" class="form-control" name="user_email" value="" />
                            </div>

                            <div class="form-group">
                                <label>パスワード</label>
                                <input type="password" class="form-control" name="user_password" value="" />
                            </div>

                            <div class="form-group">
                                <input type="submit" value="ログイン" class="btn btn-primary btn-block">
                            </div>

                            <div class="form-group">
                                <input type="checkbox">次回から自動でログイン
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/col-md-3-->

        </div>
        <!--/row-->

        <hr>
        <footer class="footer">
            <p>&copy; SenseShare</p>
        </footer>

    </div>
    <!--/.container-->

</body>

</html>