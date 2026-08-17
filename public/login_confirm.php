<?php
// ＜入力＞
// 入力・送信値取得
$login = $_POST['login'];
$password = $_POST['password'];

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$username = 'staff';
$dbPassword = 'password';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// 例外処理
try {
    // データベース接続
    $pdo = new PDO($dsn, $username, $dbPassword, $options);

    // パスワードのハッシュ認証
    // クエリ準備
    $sqlSelectPassword = 'SELECT password FROM customer WHERE login = :login';
    $stmtSelectPassword = $pdo->prepare($sqlSelectPassword);

    // パラメータ設定
    $stmtSelectPassword->bindValue(':login', $login, PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectPassword->execute();

    // 結果取得
    $hash = $stmtSelectPassword->fetch(PDO::FETCH_ASSOC);

    // 検証
    $isHash = password_verify($password, $hash['password']);
    if (!$isHash) {
        exit('ログインに失敗しました。');
    }

    // ログイン検証
    // クエリ準備
    $sqlSelectCustomer = 'SELECT * FROM customer WHERE login = :login AND password = :password';
    $stmtSelectCustomer = $pdo->prepare($sqlSelectCustomer);

    // パラメータ設定
    $stmtSelectCustomer->bindValue(':login', $login, PDO::PARAM_STR);
    $stmtSelectCustomer->bindValue(':password', $hash['password'], PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectCustomer->execute();

    // 結果取得
    $result = $stmtSelectCustomer->fetch(PDO::FETCH_ASSOC);

    // ログイン検証
    if (!$result) {
        exit('ログインに失敗しました。');
    }

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'ログインに成功しました。';
