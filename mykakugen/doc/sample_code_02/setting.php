<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>設定 | 格言リマインダー マイカクゲン</title>
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
                <ul class="nav navbar-nav">
                    <li><a href="./index.php">格言登録</a></li>
                    <li><a href="./item_list.php">格言リスト</a></li>
                    <li class="active"><a href="./setting.php">設定</a></li>
                    <li><a href="./login.php">ログアウト</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>設定</h1>

        <form method="POST" class="panel panel-default panel-body">

            <div class="form-group">
                <label>メール通知</label>

                <select class="form-control" name="delivery_hour">
                    <option value="99">しない</option>
                    <option value="0">0時</option>
                    <option value="1">1時</option>
                    <option value="2">2時</option>
                    <option value="3">3時</option>
                    <option value="4">4時</option>
                    <option value="5">5時</option>
                    <option value="6">6時</option>
                    <option value="7">7時</option>
                    <option value="8">8時</option>
                    <option value="9">9時</option>
                    <option value="10">10時</option>
                    <option value="11">11時</option>
                    <option value="12">12時</option>
                    <option value="13">13時</option>
                    <option value="14">14時</option>
                    <option value="15">15時</option>
                    <option value="16">16時</option>
                    <option value="17">17時</option>
                    <option value="18">18時</option>
                    <option value="19">19時</option>
                    <option value="20">20時</option>
                    <option value="21">21時</option>
                    <option value="22">22時</option>
                    <option value="23">23時</option>
                </select>
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="登録">
            </div>
        </form>

        <a href="./index.php">戻る</a>

        <hr>
        <footer class="footer">
            <p>&copy; SenseShare</p>
        </footer>

    </div>
    <!--/.container-->
</body>

</html>