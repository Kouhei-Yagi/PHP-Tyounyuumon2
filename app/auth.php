<?php
// 関数ファイルの読み込み
require_once(__DIR__ . '/db.php');

/**
 * ログインユーザー取得
 *
 * @param string $dsn データソース名
 * @param string $username ユーザー名
 * @param string $dbPassword データベースパスワード
 * @param array $fields 入力フィールド
 * @return array{id:int,name:string,address:string,login:string,password:string,created_at:string}|null ログインユーザー情報 or null
 */
function getUserByLogin(string $dsn, string $username, string $dbPassword, array $fields): ?array
{
    // データベース接続
    $pdo = getDbConnection($dsn, $username, $dbPassword);

    // クエリ準備
    $sqlSelectCustomer = 'SELECT * FROM customer WHERE login = :login';
    $stmtSelectCustomer = $pdo->prepare($sqlSelectCustomer);

    // パラメータ設定
    $stmtSelectCustomer->bindValue(':login', $fields['login'], PDO::PARAM_STR);

    // クエリ実行
    $stmtSelectCustomer->execute();

    // 結果取得
    $customer = $stmtSelectCustomer->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        return null;
    }

    return $customer;
}
