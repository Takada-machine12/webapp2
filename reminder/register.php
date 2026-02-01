<?php

    $host = 'mysql3112.db.sakura.ne.jp';

    $user = 'goldcivet74_sample01';

    $pass = 'takada1278';

    $db = 'goldcivet74_sample01';



    $param = 'mysql:dbname='.$db.';host='.$host;

    $pdo = new PDO($param, $user, $pass);

    $pdo->query('SET NAMES utf8;');



    if ($_POST['id']) {

        $stmt = $pdo->prepare('UPDATE reminder SET title = :title, remind_date = :remind_date WHERE id = :id');

        $stmt->bindValue(':id', $_POST['id']);

        $stmt->bindValue(':title', $_POST['title']);

        $stmt->bindValue(':remind_date', $_POST['remind_date']);

    } else {

        $stmt = $pdo->prepare('INSERT INTO reminder (title, remind_date) VALUES (:title, :remind_date)');

        $stmt->bindValue(':title', $_POST['title']);

        $stmt->bindValue(':remind_date', $_POST['remind_date']);

    }

    $flag = $stmt->execute();

    unset($pdo);



    header('Location: index.php');

    exit;

?>