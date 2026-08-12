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

/**
 * 入力値文字数チェック
 *
 * @param array $rules 最大文字数
 * @param array $fields trim 後の入力フィールド
 * @return array{key:string,max:int}|null キー名 + 最大文字数 or null
 */
function validateLength(array $rules, array $fields): ?array
{
    foreach ($rules as $key => $max) {
        if (mb_strlen($fields[$key]) > $max) {
            return [
                'key' => $key,
                'max' => $max,
            ];
        }
    }

    return null;
}

/**
 * 入力値バリデーション
 *
 * @param array $rawFields trim 前入力フィールド
 * @param array $fields trim 後入力フィールド
 * @param array $rules 最大文字数
 * @return array{isExists:bool,errorKey:?string,errorArray:?array}
 */
function validateFields(array $rawFields, array $fields, array $rules): array
{
    // 入力値存在チェック
    $isExists = validateExists($rawFields);

    // 入力値空欄チェック
    $errorKey = validateRequired($fields);

    // 入力値文字数チェック
    $errorArray = validateLength($rules, $fields);

    return [
        'isExists' => $isExists,
        'errorKey' => $errorKey,
        'errorArray' => $errorArray,
    ];
}
