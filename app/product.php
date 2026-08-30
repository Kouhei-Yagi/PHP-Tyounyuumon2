<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/db.php');

/**
 * 商品一覧取得
 *
 * @return array{id:int,name:string,price:int} 取得した商品一覧
 */
function getProducts(): array
{
    // データベース接続
    $pdo = getDbConnection();

    // クエリ実行
    $sqlSelectProducts = 'SELECT * FROM product';
    $stmtSelectProducts = $pdo->query($sqlSelectProducts);

    // 結果取得
    return $stmtSelectProducts->fetchAll(PDO::FETCH_ASSOC);
}
