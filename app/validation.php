<?php

/**
 * 入力値存在チェック
 *
 * @param array $rawFields 生の入力フィールド一覧
 * @return bool
 */
function validateExists(array $rawFields): bool
{
    foreach ($rawFields as $key => $value) {
        if ($value === null) {
            return false;
        }
    }

    return true;
}

/**
 * 入力値空欄チェック
 *
 * @param array $fields trim 後の入力フィールド一覧
 * @return string|null キー名 or null
 */
function validateRequired(array $fields): ?string
{
    foreach ($fields as $key => $value) {
        if ($value === '') {
            return $key;
        }
    }

    return null;
}
