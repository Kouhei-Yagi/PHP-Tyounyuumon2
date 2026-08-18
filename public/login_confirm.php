<?php
// ＜入力＞
// 入力・送信値取得
$login = filter_input(INPUT_POST, 'login');
$password = filter_input(INPUT_POST, 'password');

// 入力フィールド一覧
$rawFields = [
    'login' => $login,
    'password' => $password,
];

// 入力値存在チェック
foreach ($rawFields as $key => $value) {
    if ($value === null) {
        exit('不正なアクセスです。');
    }
}

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

    // ログイン検証
    // クエリ準備
    $sqlSelectCustomer = 'SELECT * FROM customer WHERE login = :login';
    $stmtSelectCustomer = $pdo->prepare($sqlSelectCustomer);

    // パラメータ設定
    $stmtSelectCustomer->bindValue(':login', $login, PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectCustomer->execute();

    // 結果取得
    $customer = $stmtSelectCustomer->fetch(PDO::FETCH_ASSOC);

    // ログイン名存在チェック
    if (!$customer) {
        exit('ログインに失敗しました。');
    }

    // パスワードのハッシュ検証
    $isHash = password_verify($password, $customer['password']);
    if (!$isHash) {
        exit('ログインに失敗しました。');
    }

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'ログインに成功しました。';
