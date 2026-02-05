<?php 

try {
    $dsn = 'mysql:host=localhost;dbname=interplan_pizza;charset=utf8';
    $user = 'pizza_taro';
    $pass = 'X_A]SErTETio/kwq';
    $option = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $db = new PDO($dsn,$user,$pass,$option);

} catch(PDOException $e) {
    // var_dump($e->errorInfo[2]);
    echo $e->getMessage();
}