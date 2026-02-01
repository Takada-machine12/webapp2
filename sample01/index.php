<html>

  <head>

    <title>お問い合わせフォーム</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  </head>

  <body>

    <form action="send.php" method="post">

      <?php

        echo "入力内容をご確認下さい。<br /><br />";

        echo "氏名：".$_POST['name']."<br />";

        echo "氏名（カナ）：".$_POST['kana']."<br />";

        echo "メールアドレス：".$_POST['mail']."<br />";

        echo "住所：".$_POST['address']."<br />";

        echo "電話番号：".$_POST['tel']."<br />";

        echo "お問い合わせ内容：".$_POST['comment']."<br /><br />";

      ?>

      <input type="hidden" name="name" value="<?php echo $_POST['name'] ?>" />

      <input type="hidden" name="kana" value="<?php echo $_POST['kana'] ?>" />

      <input type="hidden" name="mail" value="<?php echo $_POST['mail'] ?>" />

      <input type="hidden" name="address" value="<?php echo $_POST['address'] ?>" />

      <input type="hidden" name="tel" value="<?php echo $_POST['tel'] ?>" />

      <input type="hidden" name="comment" value="<?php echo $_POST['comment'] ?>" />

      <input type="submit" value="送信" />

    </form>

  </body>

</html>