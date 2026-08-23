<?php

/**
 * CSRFトークン生成
 *
 * @return string 生成したトークン
 */
function generateCsrfToken(): string
{
    return bin2hex(random_bytes(32));
}

/**
 * CSRFトークン検証
 *
 * @param string|null $csrfToken POST送信されてきたCSRFトークン
 * @return bool
 */
function validateCsrfToken(?string $csrfToken): bool
{
    // CSRFトークン存在チェック
    if ($csrfToken === null) {
        return false;
    }

    // CSRFトークン検証
    if ($csrfToken !== $_SESSION['csrf_token']) {
        return false;
    }

    // CSRFトークン破棄
    unset($_SESSION['csrf_token']);

    return true;
}
