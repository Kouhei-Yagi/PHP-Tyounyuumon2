<?php
// ＜前処理＞
// セッション開始
session_start();

// ＜入力＞
// 送信値取得
$csrfToken = $_POST['csrf_token'];

// CSRFトークン存在チェック・検証
if (!isset($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
    exit('不正なアクセスです。');
}

// CSRFトークン破棄
unset($_SESSION['csrf_token']);

// ＜処理＞
// ログインユーザー情報のセッション破棄
unset($_SESSION['auth']);

// ＜出力＞
echo 'ログアウトしました。';
