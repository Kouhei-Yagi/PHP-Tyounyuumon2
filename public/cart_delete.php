<?php
// ＜簡易設計書＞
// 機能：カート商品削除
// 目的：カート一覧から商品を削除する
// 要件：ログインユーザーのカート一覧から商品が削除される
// 画面：カート一覧画面（削除ボタン） → カート削除処理
// データ：顧客ID、商品ID
// テーブル：cartテーブル

// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/security.php');
require_once(__DIR__ . '/../app/validation.php');
require_once(__DIR__ . '/../app/cart.php');

// セッション開始
session_start();

// ログインチェック
if (!isset($_SESSION['auth'])) {
    exit('ログインしてください。');
}

// POST 送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// ＜入力＞
// 送信値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');
$customerId = $_SESSION['auth']['id'];
$productId = filter_input(INPUT_POST, 'product_id');

// CSRF トークン存在チェック・検証・破棄
$isCsrfToken = validateCsrfToken($csrfToken);
if (!$isCsrfToken) {
    exit('不正なアクセスです。');
}

// 商品IDバリデーション
$validatedId = validateProductId($productId);
if (!$validatedId) {
    exit('不正なアクセスです。');
}

// 商品IDを整数型に変換
$productIdInt = (int)$productId;

// ＜処理＞
// 例外処理
try {
    // カート商品削除
    deleteCartItem($customerId, $productIdInt);

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'カートから商品を削除しました。';
