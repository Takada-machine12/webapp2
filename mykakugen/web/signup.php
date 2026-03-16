<?php
//エラー時の表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  //初めて画面にアクセスした時の処理(ユーザーがブラウザでアクセスし、ただ見にきた時。)
  //CSRF対策
  setToken();
} else {
  //フォームからサブミットされた時の処理(入力フォームから「アカウント作成」ボタンが押された時→フォームを送信した時。)
  //CSRF対策
  checkToken();

  //入力されたニックネーム、メールアドレス、パスワードを受け取り、変数に入れる。
  $user_screen_name = $_POST['user_screen_name'];
  $user_password = $_POST['user_password'];
  $user_email = $_POST['user_email'];

  //エラー定義
  $error = array();

  //入力チェック
  if ($user_screen_name == '') {
    $error['user_screen_name'] = 'ニックネームを入力してください。';
  }

  //[パスワード]入力チェック
  if ($user_password == '') {
    $error['user_password'] = 'パスワードを入力してください。';
  }

  //[メールアドレス]入力チェック
  if ($user_email == '') {
    $error['user_email'] = 'メールアドレスを入力してください。';
  } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $error['user_email'] = '正しくないメールアドレスです。';
  }

  //上記エラーがない場合の処理
  if (empty($error)) {
    //データベースに接続する。(PDOを使う。)
    $pdo = connectDb();

    //[メールアドレス]存在チェック(重複登録を防ぐため。)→DB側でもユニーク制約設定
    if (checkEmail($user_email, $pdo)) {
      $error['user_email'] = 'このメールアドレスは既に登録されています。';
      unset($pdo); //エラー時DB接続を切断
    } else {
      //データベース(userテーブル)に新規登録する。
      $sql = "INSERT INTO user(user_screen_name, user_password, user_email, delivery_hour, created_at, updated_at) 
                            VALUES(:user_screen_name, :user_password, :user_email, 99, now(), now())";
      $stmt = $pdo->prepare($sql); //実行したいSQL文をセットする。
      $stmt->bindValue(':user_screen_name',$user_screen_name);
      $stmt->bindValue(':user_password',$user_password);
      $stmt->bindValue(':user_email',$user_email);
      $flag = $stmt->execute(); //SQLを実行

      //新規登録者を管理者にメールで通知する。
      mb_language('japanese');
      mb_internal_encoding('UTF-8');

      $to = ADMIN_MAIL_ADDRESS;
      $mail_title = '【マイカクゲン】新規ユーザー登録がありました。';
      $mail_body = 'ニックネーム:'.$user['user_screen_name'].PHP_EOL;
      $mail_body.= 'メールアドレス:'.$user['user_email'];

      mb_send_mail($to, $mail_title, $mail_body);

      //自動ログイン
      $first_user = getUser($user_email, $user_password, $pdo);
      $_SESSION['USER'] = $first_user;

      unset($pdo); //DB接続を切断

      //ログイン後に毎回セッションIDを書き換える。
      session_regenerate_id(true);

      //signup_complete.phpに画面遷移する。
      header('Location: '.SITE_URL.'signup_complete.php');

      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>新規ユーザー登録 | <?php echo SERVICE_NAME; ?></title>
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
                <li><a href="./index.php">格言登録</a></li>
                <li><a href="./item_list.php">格言リスト</a></li>
                <li><a href="./setting.php">設定</a></li>
                <li><a href="./logout.php">ログアウト</a></li>
              </ul>
            </div>
        </div>
    </div>

    <div class="container">
      <h1>ユーザー登録</h1>
      <form method="POST" class="panel panel-default panel-body">

        <div class="form-group <?php echo !empty($error['user_screen_name']) ? "has-error" : ''; ?>"><!-- エラー時のみ赤く表示 -->
          <label>ニックネーム</label>
          <input type="text" name="user_screen_name" class="form-control" placeholder="ニックネーム" value="<?php echo xss($user_screen_name ??''); ?>" />
          <span class="help-block"><?php if (!empty($error['user_screen_name'])) echo xss($error['user_screen_name']); ?></span>
        </div>

        <div class="form-group <?php echo !empty($error['user_password']) ? "has-error" : ''; ?>">
          <label>パスワード</label>
          <input type="password" name="user_password" class="form-control" placeholder="パスワード" value="" />
          <span class="help-block"><?php if (!empty($error['user_password'])) echo xss($error['user_password']); ?></span>
        </div>

        <div class="form-group <?php echo !empty($error['user_email']) ? "has-error" : ''; ?>">
          <label>メールアドレス</label>
          <input type="text" name="user_email" class="form-control" placeholder="メールアドレス" value="" />
          <span class="help-block"><?php if (!empty($error['user_email'])) echo xss($error['user_email']); ?></span>
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-success btn-block" value="アカウント作成"/>
        </div>
        <input type="hidden" name="token" value="<?php echo xss($_SESSION['sstoken']); ?>" />
        
      </form>
    </div><!-- container -->
    <hr>
    <footer class="footer">
      <p><?php echo COPYRIGHT; ?></p>
    </footer>
  </body>
</html>