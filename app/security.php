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
