# PHP-Tyouunyuumon2

書籍『確かな力が身につくPHP「超」入門 第2版』の学習用リポジトリです。

## Git 運用ルール

このリポジトリでは、学習効率を高めるために 簡易版 GitHub Flow を採用しています。
実務的な運用を意識しつつ、プルリクエストを使わずに main に直接マージする運用を行います。

### ブランチ運用ルール

#### ブランチ種別

- **main** : 常に安定した状態を保つ主ブランチ
- **feature/** : 各章の学習内容を進めるための作業ブランチ
- **refactor/** : 既存コードやディレクトリ構成の整理・改善（機能変更なし）

#### ブランチ命名例

```sh
feature/chapter2-first-script
```

#### 開発フロー

1. main から作業ブランチを作成

```sh
git switch main
git pull origin main
git switch -c feature/xxx
```

2. 作業ブランチで学習内容を実装
3. 学習内容を適切な粒度でコミット

```sh
git add xxx
git commit -m "xxx"
```

4. 作業ブランチを GitHub に push

```sh
git push origin feature/xxx -u
```

5. 作業が完了したら main にマージ

```sh
git switch main
git pull origin main
git merge feature/xxx --no-ff --no-edit
```

6. マージ後に GitHub へ push

```sh
git push origin main
```

7. 作業ブランチを削除する（ローカル → リモートの順）

```sh
git branch -d feature/xxx
git push origin --delete feature/xxx
```

### コミットメッセージ規約（Conventional Commits）

コミットメッセージは 「プレフィックス（英語）＋ 内容（日本語）」 の形式で記述します。

#### プレフィックス一覧

- **feat** : 新しい学習内容の追加
- **fix** : 誤りの修正
- **refactor** : コード整理
- **style** : コード整形（動作に影響なし）
- **docs** : ドキュメント更新
- **test** : テスト追加
- **chore** : 環境設定・依存更新
- **remove** : 不要ファイルの削除

#### コミットメッセージ例

```sh
feat: [chapter2-welcome.php] echoでメッセージを表示する処理を実装
chore: リポジトリ初期化のためREADMEを追加
docs: Git運用ルールをREADMEに追記
```

## 開発ロードマップ（chapter7: ECサイト風アプリ）

- [ ] feature/chapter7-db-schema
- [ ] feature/chapter7-auth-register
- [ ] feature/chapter7-auth-login
- [ ] feature/chapter7-auth-logout
- [ ] feature/chapter7-auth-middleware
- [ ] refactor/chapter7-extract-db-connection
- [ ] feature/chapter7-profile-update
- [ ] feature/chapter7-product-list
- [ ] feature/chapter7-cart-add
- [ ] feature/chapter7-cart-update
- [ ] feature/chapter7-favorite-toggle
- [ ] feature/chapter7-purchase-checkout
- [ ] feature/chapter7-purchase-history
- [ ] feature/chapter7-product-search（任意）
- [ ] feature/chapter7-security-hardening（任意）
