<?php
// ＜前処理＞
// セッション開始
session_start();

// ログインチェック
if (!isset($_SESSION['auth'])) {
    exit('ログインしてください。');
}

// ＜入力＞
// 送信・選択値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');
$customerId = $_SESSION['auth']['id'];
$productId = filter_input(INPUT_POST, 'id');
$count = filter_input(INPUT_POST, 'count');

// csrf トークン存在チェック
if ($csrfToken === null) {
    exit('不正なアクセスです。');
}

// csrf トークン検証
if ($csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// csrf トークン破棄
unset($_SESSION['csrf_token']);

// 送信・選択値存在チェック
if ($productId === null || $count === null) {
    exit('不正なアクセスです。');
}

// 送信・選択値空欄チェック
if ($productId === '' || $count === '') {
    exit('不正なアクセスです。');
}

// 送信・選択値数値チェック
if (!ctype_digit($productId) || !ctype_digit($count)) {
    exit('不正なアクセスです。');
}

// 選択値範囲チェック
if ($count < 1 || $count > 10) {
    exit('不正なアクセスです。');
}

// 商品IDと個数を整数型に変換
$productIdInt = (int)$productId;
$countInt = (int)$count;

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
    $stmtInsertCart->bindValue('product_id', $productIdInt, PDO::PARAM_INT);
    $stmtInsertCart->bindValue('count', $countInt, PDO::PARAM_INT);

    // クエリ実行
    $stmtInsertCart->execute();

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'カートに登録しました。';
