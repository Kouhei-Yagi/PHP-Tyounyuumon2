<?php

/**
 * データベース接続
 *
 * @param string $dsn データソース
 * @param string $username ユーザー名
 * @param string $dbPassword パスワード
 * @return PDO
 */
function getDbConnection(string $dsn, string $username, string $dbPassword): PDO
{
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $username, $dbPassword, $options);
}
