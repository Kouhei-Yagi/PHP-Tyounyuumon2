<?php
// ＜前処理＞
// セッション開始
session_start();

// CSRFトークン生成
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログアウト</title>
</head>

<body>
    <p>ログアウトしますか？</p>

    <form action="logout_confirm.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit">ログアウト</button>
    </form>
</body>

</html>
