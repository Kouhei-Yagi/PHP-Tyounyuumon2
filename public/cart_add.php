<?php
// ＜前処理＞
// セッション開始
session_start();

// ログインチェック
if (!isset($_SESSION['auth'])) {
    exit('ログインしてください。');
}

// ＜入力＞
// 送信・入力値取得
$customerId = $_SESSION['auth']['id'];
$productId = filter_input(INPUT_POST, 'id');
$count = filter_input(INPUT_POST, 'count');

// 入力値存在チェック
if ($productId === null || $count === null) {
    exit('不正なアクセスです。');
}

// 入力値空欄チェック
if ($productId === '' || $count === '') {
    exit('不正なアクセスです。');
}

// ＜処理＞
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$user = 'staff';
$dbPassword = 'password';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// 例外処理
try {
    // データベース接続
    $pdo = new PDO($dsn, $user, $dbPassword, $options);

    // カート登録
    // クエリ準備
    $sqlInsertCart = 'INSERT INTO cart(customer_id, product_id, count) VALUES (:customer_id, :product_id, :count)';
    $stmtInsertCart = $pdo->prepare($sqlInsertCart);

    // クエリパラメータ設定
    $stmtInsertCart->bindValue('customer_id', $customerId, PDO::PARAM_INT);
    $stmtInsertCart->bindValue('product_id', $productId, PDO::PARAM_INT);
    $stmtInsertCart->bindValue('count', $count, PDO::PARAM_INT);

    // クエリ実行
    $stmtInsertCart->execute();

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'カートに登録しました。';
