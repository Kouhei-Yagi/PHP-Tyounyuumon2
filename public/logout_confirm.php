<?php
// ＜前処理＞
// セッション開始
session_start();

// ＜処理＞
// ログインユーザー情報のセッション破棄
unset($_SESSION['auth']);

// ＜出力＞
echo 'ログアウトしました。';
