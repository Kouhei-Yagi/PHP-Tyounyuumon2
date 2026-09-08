<?php
// ＜前処理＞
// セッション開始
session_start();

// ログインチェック
if (!isset($_SESSION['auth'])) {
    exit('ログインしてください。');
}

// ＜入力＞
// POST 送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 送信・選択値取得
$customerId = $_SESSION['auth']['id'];
$csrfToken = filter_input(INPUT_POST, 'csrf_token');
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

// 商品IDと個数を整数型に変換
$productIdInt = (int)$productId;
$countInt = (int)$count;

// 選択値範囲チェック
if ($countInt < 1 || $countInt > 10) {
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

    // カート情報取得
    // クエリ準備
    $sqlSelectCart = 'SELECT * FROM cart WHERE customer_id = :customer_id AND product_id = :product_id';
    $stmtSelectCart = $pdo->prepare($sqlSelectCart);

    // パラメータ設定
    $stmtSelectCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $stmtSelectCart->bindValue(':product_id', $productIdInt, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectCart->execute();

    // 結果取得
    $cartItem = $stmtSelectCart->fetch(PDO::FETCH_ASSOC);

    // 既に同じ商品があればカート更新・なければカート登録
    if ($cartItem) {
        // カート更新
        // クエリ準備
        $sqlUpdateCart = 'UPDATE cart SET count = :count WHERE customer_id = :customer_id AND product_id = :product_id';
        $stmtUpdateCart = $pdo->prepare($sqlUpdateCart);

        // パラメータ設定
        $stmtUpdateCart->bindValue(':count', $countInt, PDO::PARAM_INT);
        $stmtUpdateCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
        $stmtUpdateCart->bindValue(':product_id', $productIdInt, PDO::PARAM_INT);

        // クエリ実行
        $stmtUpdateCart->execute();
    } else {
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
    }

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
if ($cartItem) {
    echo 'カート情報を更新しました。';
} else {
    echo 'カートに商品を登録しました。';
}
