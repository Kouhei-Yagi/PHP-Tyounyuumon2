<?php
// ＜簡易設計書＞
// 機能：カート商品削除処理
// 目的：カート一覧から商品を削除する
// 要件：ログインユーザーのカート一覧から商品が削除される
// 画面：カート一覧画面（削除ボタン） → カート削除処理
// データ：顧客ID、商品ID
// テーブル：cartテーブル

// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/db.php');

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

// CSRF トークン存在チェック
if ($csrfToken === null) {
    exit('不正なアクセスです。');
}

// CSRF トークン検証
if ($csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// CSRF トークン破棄
unset($_SESSION['csrf_token']);

// 商品ID存在チェック
if ($productId === null) {
    exit('不正なアクセスです。');
}

// 商品IDの値空欄チェック
if ($productId === '') {
    exit('不正なアクセスです。');
}

// 商品IDの値数値チェック
if (!ctype_digit($productId)) {
    exit('不正なアクセスです。');
}

// 商品IDを整数型に変換
$productIdInt = (int)$productId;

// ＜処理＞
// 例外処理
try {
    // データベース接続
    $pdo = getDbConnection();

    // カート商品削除
    // クエリ準備
    $sqlDeleteCart = '
    DELETE FROM cart
    WHERE customer_id = :customer_id
        AND product_id = :product_id
';

    $stmtDeleteCart = $pdo->prepare($sqlDeleteCart);

    // パラメータ設定
    $stmtDeleteCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $stmtDeleteCart->bindValue(':product_id', $productIdInt, PDO::PARAM_INT);

    // クエリ実行
    $stmtDeleteCart->execute();

    // 例外発生時処理
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('システムエラーが発生しました。');
}

// ＜出力＞
echo 'カートから商品を削除しました。';
