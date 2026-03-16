<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
    echo '<html><head><meta charset="utf-8"></head><body>不正なアクセスです。</body></html>';
    exit;
} else {
    $pdo = connectDb();

    // 現在の時刻(hour)を配信時刻にしているユーザを全て抽出する。
    $stmt = $pdo->prepare("SELECT *  FROM user WHERE delivery_hour = :delivery_hour");
    $stmt->execute(array(":delivery_hour" => date('G')));
    $users = $stmt->fetchAll();

    // 抽出したユーザでループする。
    foreach ($users as $user) {

        // 対象ユーザの格言を全て取得する。
        $stmt2 = $pdo->prepare("SELECT *  FROM item WHERE user_id = :user_id");
        $stmt2->execute(array(":user_id" => $user['id']));
        $items = $stmt2->fetchAll();

        if ($items) {
            // 取得した格言配列からランダムに1件引き抜く。
            $rand_no = array_rand($items);
            $target_item = $items[$rand_no];

            // メール送信する。
            $mail_title = '【マイカクゲン】今日のカクゲン';
            $mail_body = '『' . $target_item['item_text'] . '』' . PHP_EOL;

            if (!empty($mail_body)) {
                mb_language("japanese");
                mb_internal_encoding("UTF-8");
                mb_send_mail($user['user_email'], $mail_title, $mail_body);
            }
        }
    }

    unset($pdo);
    exit;
}
