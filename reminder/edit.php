<?php

    $id = '';

    $title = '';

    $remind_date = '';



    if ($_GET['id']) {

    $host = 'mysql3112.db.sakura.ne.jp';

    $user = 'goldcivet74_sample01';

    $pass= 'takada1278';

    $db = 'goldcivet74_sample01';



    $param = 'mysql:dbname='.$db.';host='.$host;

    $pdo = new PDO($param, $user, $pass);

    $pdo->query('SET NAMES utf8;');



    $stmt = $pdo->prepare('SELECT * FROM reminder where id = :id');

    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    $flag = $stmt->execute();



    $row = $stmt->fetch();

    $id = $row['id'];

    $title = $row['title'];

    $remind_date = $row['remind_date'];



    unset($pdo);

    }

?>

<html>

  <head>

    <title>登録画面 | リマインダー</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  </head>

  <body>

    <h1>登録画面</h1>

    <form action="register.php" method="post">

      <input type="hidden" name="id" value="<?php echo $id ?>" />

      <table>

        <tr><td>タイトル</td><td><input type="text" name="title" value="<?php echo $title ?>" /></td></tr>

        <tr><td>期日</td><td><input type="text" name="remind_date" value="<?php echo $remind_date ?>" /></td></tr>

      </table>

      <input type="submit" value="登録" />

    </form>

  </body>

</html>