<?php
// ＜前処理＞
// セッション開始
session_start();

// ＜入力＞
// POST送信チェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 送信値取得
$csrfToken = filter_input(INPUT_POST, 'csrf_token');

// CSRFトークン存在チェック・検証
if ($csrfToken === null || $csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// CSRFトークン破棄
unset($_SESSION['csrf_token']);

// ＜処理＞
// ログインユーザー情報のセッション破棄
unset($_SESSION['auth']);

// ＜出力＞
echo 'ログアウトしました。';
