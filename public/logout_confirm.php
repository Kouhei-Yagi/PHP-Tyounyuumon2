<?php
// ＜前処理＞
// 関数ファイルの読み込み
require_once(__DIR__ . '/../app/security.php');

// セッション開始
session_start();

// ＜入力＞
// POST送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 送信値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');

// CSRFトークン存在チェック・検証・破棄
$isCsrfToken = validateCsrfToken($csrfToken);
if (!$isCsrfToken) {
    exit('不正なアクセスです。');
}

// ＜処理＞
// ログインユーザー情報のセッション破棄
unset($_SESSION['auth']);

// ＜出力＞
echo 'ログアウトしました。';
