<?php
// 関数ファイル読み込み
require_once(__DIR__ . '/db.php');

/**
 * カート情報取得
 *
 * @param int $customerId 顧客ID
 * @param int $productId 商品ID
 * @param PDO $pdo PDOオブジェクト
 * @return array{customer_id:int,product_id:int,count:int}|bool カート情報 or false
 */
function getCartItem(int $customerId, int $productId, PDO $pdo): array|bool
{
    // クエリ準備
    $sqlSelectCart = 'SELECT * FROM cart WHERE customer_id = :customer_id AND product_id = :product_id';
    $stmtSelectCart = $pdo->prepare($sqlSelectCart);

    // パラメータ設定
    $stmtSelectCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $stmtSelectCart->bindValue(':product_id', $productId, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectCart->execute();

    // 結果取得
    return $stmtSelectCart->fetch(PDO::FETCH_ASSOC);
}

/**
 * カート情報更新
 *
 * @param int $customerId 顧客ID
 * @param int $productId 商品ID
 * @param int $count 商品個数
 * @param PDO $pdo
 */
function updateCartItem(int $customerId, int $productId, int $count, PDO $pdo): void
{
    // クエリ準備
    $sqlUpdateCart = 'UPDATE cart SET count = :count WHERE customer_id = :customer_id AND product_id = :product_id';
    $stmtUpdateCart = $pdo->prepare($sqlUpdateCart);

    // パラメータ設定
    $stmtUpdateCart->bindValue(':count', $count, PDO::PARAM_INT);
    $stmtUpdateCart->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $stmtUpdateCart->bindValue(':product_id', $productId, PDO::PARAM_INT);

    // クエリ実行
    $stmtUpdateCart->execute();
}
