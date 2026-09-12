<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/security.php');
require_once(__DIR__ . '/../app/db.php');
require_once(__DIR__ . '/../app/validation.php');
require_once(__DIR__ . '/../app/cart.php');

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
$isCsrfToken = validateCsrfToken($csrfToken);
if (!$isCsrfToken) {
    exit('不正なアクセスです。');
}

// 送信・選択値バリデーション
$validatedId = validateProductId($productId);
$validatedCount = validateCartCount($count);

// 送信・選択値存在・空欄・数値・範囲チェック
if ($validatedId === false || $validatedCount === false) {
    exit('不正なアクセスです。');
}

// 商品IDと個数を整数型に変換
$productIdInt = (int)$productId;
$countInt = (int)$count;

// ＜処理＞
// 例外処理
try {
    // データベース接続
    $pdo = getDbConnection();

    // カート情報取得
    $cartItem = getCartItem($customerId, $productIdInt, $pdo);

    // 既に同じ商品があればカート情報更新・なければカート情報登録
    if ($cartItem) {
        // カート情報更新
        updateCartItem($customerId, $productIdInt, $countInt, $pdo);
    } else {
        // カート情報登録
        insertCartItem($customerId, $productId, $count, $pdo);
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
