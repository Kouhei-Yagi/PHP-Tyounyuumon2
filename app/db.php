<?php

/**
 * データベース接続
 *
 * @return PDO
 */
function getDbConnection(): PDO
{
    // 設定ファイルの読み込み
    $dbConfig = require_once(__DIR__ . '/config/database.php');

    // データベース接続設定
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $user = $dbConfig['user'];
    $password = $dbConfig['password'];
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $user, $password, $options);
}
