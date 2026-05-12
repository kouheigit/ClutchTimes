# Python（Django）実装版 技術シート

## プロジェクト概要
ニュース管理・投票システムのWebアプリケーションをPython（Django）で実装。

## 使用技術スタック

### バックエンド
- **Python 3.9以上**
- **Django 4.2以上** - Webフレームワーク
- **Django REST Framework** - API開発（必要に応じて）
- **django-allauth** - 認証・メール認証機能
- **Pillow** - 画像処理（必要に応じて）

### データベース
- **PostgreSQL 14以上** / **MySQL 8.0以上** - 本番環境
- **SQLite** - 開発環境

### フロントエンド
- **Django Templates** - サーバーサイドレンダリング
- **Bootstrap 5** / **Tailwind CSS** - UIフレームワーク
- **JavaScript (Vanilla)** - クライアントサイド処理

### インフラ・デプロイ
- **Docker / Docker Compose** - コンテナ化
- **Nginx** - リバースプロキシ・静的ファイル配信
- **Gunicorn** / **uWSGI** - WSGIサーバー
- **AWS / GCP / Heroku** - クラウドデプロイ

### 開発ツール
- **pytest / pytest-django** - テストフレームワーク
- **black / flake8** - コードフォーマッター・リンター
- **mypy** - 型チェック（オプション）

## 実装機能

### 1. 認証システム
- **ユーザー認証**
  - メールアドレス・パスワード認証
  - メール認証機能（email_verified_at）
  - セッション管理
  - パスワードリセット機能

- **管理者認証**
  - カスタム認証バックエンド実装
  - 管理者専用ログイン画面
  - 管理者専用ミドルウェア

### 2. ニュース管理機能（管理者）
- ニュース記事の作成・投稿
- 予約投稿機能（日時指定）
- 記事一覧表示（公開済み・予約投稿の分離）
- 記事詳細表示
- 記事削除機能
- 公開日時による自動公開制御

### 3. 投票・ベット機能
- **質問管理（管理者）**
  - 質問の作成（タイトル・質問文・選択肢3つ）
  - 投票開始日時・終了日時の設定
  - 質問一覧表示

- **投票機能（ユーザー）**
  - 投票可能な質問一覧表示
  - 投票フォーム表示
  - 投票処理（重複投票防止）
  - 投票結果のリアルタイム集計・表示
  - パーセンテージ表示

### 4. ホーム画面（ユーザー）
- 最新ニュース3件の表示
- ニュース記事へのリンク
- 投票可能な質問へのアクセス

## 技術的な実装詳細

### アーキテクチャ
- **MVCパターン**（DjangoのMTVパターン）
- **アプリケーション分割**
  - `accounts` - ユーザー認証
  - `admin_app` - 管理者機能
  - `news` - ニュース管理
  - `polls` - 投票機能

### データベース設計
- **users** - ユーザー情報
- **admins** - 管理者情報
- **admin_news_table** - ニュース記事
- **questions** - 投票質問
- **user_answers** - ユーザーの投票履歴

### セキュリティ対策
- CSRF保護（Django標準）
- XSS対策（テンプレートエスケープ）
- SQLインジェクション対策（ORM使用）
- パスワードハッシュ化（bcrypt）
- セッション管理
- 認証ミドルウェアによるアクセス制御

### パフォーマンス最適化
- データベースクエリ最適化（select_related, prefetch_related）
- キャッシュ機能（Redis / Memcached）
- 静的ファイルのCDN配信
- データベースインデックス設定

## 開発規模の目安

### 工数見積もり
- **設計・環境構築**: 3-5日
- **認証システム実装**: 5-7日
- **ニュース管理機能**: 5-7日
- **投票機能**: 7-10日
- **フロントエンド実装**: 5-7日
- **テスト・デバッグ**: 5-7日
- **デプロイ・ドキュメント**: 3-5日

**合計**: 約33-48日（1.5-2ヶ月）

### コード規模の目安
- **Pythonコード**: 約2,000-3,000行
- **テンプレート**: 約15-20ファイル
- **モデル**: 5-6モデル
- **ビュー**: 約15-20ビュー
- **フォーム**: 約5-7フォーム

## Django実装の特徴・利点

### Laravel（PHP）との比較

| 項目 | Laravel | Django |
|------|---------|--------|
| **認証** | 複数ガード設定 | カスタムバックエンド |
| **ORM** | Eloquent | Django ORM（より強力） |
| **管理画面** | 手動作成 | 標準adminパネル利用可能 |
| **バリデーション** | FormRequest | Forms（統合） |
| **テスト** | PHPUnit | pytest（標準） |
| **セキュリティ** | 標準機能 | 標準機能（より厳格） |

### Djangoの利点
1. **標準管理画面**: adminパネルでCRUD操作が即座に可能
2. **強力なORM**: 複雑なクエリも簡潔に記述可能
3. **セキュリティ**: CSRF、XSS対策が標準装備
4. **テスト**: テストフレームワークが標準
5. **スケーラビリティ**: 大規模アプリケーションにも対応
6. **エコシステム**: 豊富なサードパーティパッケージ

## 実装時の技術的ポイント

### 1. カスタム認証バックエンド
```python
# admin_app/backends.py
class AdminBackend(ModelBackend):
    def authenticate(self, request, username=None, password=None, **kwargs):
        # 管理者専用の認証ロジック
```

### 2. 日時処理
- `timezone.now()` を使用したタイムゾーン対応
- 予約投稿の自動公開処理（Celeryタスクまたはcron）

### 3. 投票集計の最適化
- アノテーションを使用した効率的な集計
- キャッシュによるパフォーマンス向上

### 4. 重複投票防止
- データベース制約（unique_together）
- アプリケーションレベルでのチェック

## デプロイ構成例

### Docker構成
```
docker-compose.yml
├── web (Django + Gunicorn)
├── db (PostgreSQL)
├── nginx (リバースプロキシ)
└── redis (キャッシュ・セッション)
```

### 環境変数管理
- `.env` ファイルによる設定管理
- `django-environ` パッケージ使用

## 今後の拡張性

### 追加可能な機能
- RESTful API実装（Django REST Framework）
- WebSocket対応（Django Channels）
- リアルタイム通知機能
- 画像アップロード機能
- 全文検索機能（Elasticsearch）
- レコメンデーション機能

## 参考技術ドキュメント
- Django公式ドキュメント: https://docs.djangoproject.com/
- Django REST Framework: https://www.django-rest-framework.org/
- django-allauth: https://django-allauth.readthedocs.io/

