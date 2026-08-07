<?php
// ＜入力＞
// POST送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 入力値取得
$name = filter_input(INPUT_POST, 'name');
$address = filter_input(INPUT_POST, 'address');
$login = filter_input(INPUT_POST, 'login');
$password = filter_input(INPUT_POST, 'password');

// 入力フィールド一覧
$fields = [
    'name' => $name,
    'address' => $address,
    'login' => $login,
    'password' => $password,
];

// 入力値存在チェック
foreach ($fields as $key => $value) {
    if ($value === null) {
        exit('不正なアクセスです。');
    }
}

// ＜処理＞
// DBに登録
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

    // クエリ準備
    $sql = 'INSERT INTO customer(name, address, login, password) VALUES(:name, :address, :login, :password)';
    $stmt = $pdo->prepare($sql);

    // パラメータ設定
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':address', $address, PDO::PARAM_STR);
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);

    // クエリ実行
    $stmt->execute();

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo '登録しました。';
