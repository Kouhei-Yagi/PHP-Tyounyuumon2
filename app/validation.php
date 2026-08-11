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
