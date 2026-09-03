<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/db.php');

/**
 * 商品一覧取得
 *
 * @return array{id:int,name:string,price:int}|bool 取得した商品一覧 or false
 */
function getProducts(): array|bool
{
    // データベース接続
    $pdo = getDbConnection();

    // クエリ実行
    $sqlSelectProducts = 'SELECT * FROM product';
    $stmtSelectProducts = $pdo->query($sqlSelectProducts);

    // 結果取得
    return $stmtSelectProducts->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 商品1件取得
 *
 * @param int $idInt 数値型変換後のid
 * @return array{id:int,name:string,price:int}|bool 取得した商品データ or false
 */
function getProduct(int $idInt): array|bool
{
    // データベース接続
    $pdo = getDbConnection();

    // クエリ準備
    $sqlSelectProduct = 'SELECT * FROM product WHERE id = :id';
    $stmtSelectProduct = $pdo->prepare($sqlSelectProduct);

    // パラメータ設定
    $stmtSelectProduct->bindValue(':id', $idInt, PDO::PARAM_INT);

    // クエリ実行
    $stmtSelectProduct->execute();

    // 結果取得
    return $stmtSelectProduct->fetch(PDO::FETCH_ASSOC);
}
