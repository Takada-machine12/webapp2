<?php
require_once('config.php');
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
					<a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SERVICE_SHORT_NAME ?></a>
          <ul class="nav navbar-nav">
            <li class="active"><a href="./index.php">格言登録</a></li>
            <li><a href="./item_list.php">格言リスト</a></li>
            <li><a href="./setting.php">設定</a></li>
            <li><a href="./login.php">ログアウト</a></li>
          </ul>
				</div>
			</div>
		</div>

    <div class="container">
      <h1>HOME</h1>

      <form method="POST" class="panel panel-defalut panel-body">
        <div class="form-group">
          <label>あなたの格言を登録して下さい。</label>
          <input type="text" class="form-control" name="item_text" value="" />
        </div>
        
        <div class="form-group">
          <input type="submit" class="btn btn-success btn-block" value="登録">
        </div>

      </form>

      <hr>
      <footer class="footer">
				<p><?php echo COPYRIGHT; ?></p>
			</footer>

    </div><!--/.container-->

  </body>

</html>