# ドラEVER（doraever.jp）技術スタック分析

## 分析日
2025年12月19日

## 確認方法
- HTTPヘッダー分析
- HTMLソースコード分析
- JavaScript/CSSファイルの確認

---

## バックエンド技術スタック

### サーバーサイドフレームワーク
**Laravel (PHP)** - **高確率**

**根拠**:
1. **CSRFトークン**: `<meta name="csrf-token">` がHTMLに含まれている
   - Laravelの標準的なCSRF保護機能
   - トークン名がLaravelのデフォルト形式

2. **セッション管理**: HTTPヘッダーに以下のCookieが設定されている
   - `XSRF-TOKEN` - Laravelの標準CSRFトークン名
   - `doraever_session` - LaravelのセッションCookie名（`{app_name}_session`形式）

3. **サーバー**: `nginx` が使用されている（Laravelの一般的な構成）

### プログラミング言語
**PHP**（Laravelフレームワーク使用）

### データベース
**推測**: MySQL または PostgreSQL
- 求人情報サイトとして大量のデータを扱うため、リレーショナルデータベースを使用している可能性が高い

### キャッシュ
**推測**: Redis または Memcached
- セッション管理やデータキャッシュに使用されている可能性

---

## フロントエンド技術スタック

### JavaScriptライブラリ・フレームワーク

#### **jQuery**
- **バージョン**: jQuery 3.7.1 および jQuery 1.12.4（両方が読み込まれている）
- **用途**: DOM操作、Ajax通信、イベント処理
- **確認方法**: HTMLソースにjQuery CDNが含まれている

#### **Swiper.js**
- **バージョン**: Swiper 11
- **用途**: スライダー・カルーセル機能
- **確認方法**: `swiper@11/swiper-bundle.min.js` が読み込まれている
- **CDN**: jsDelivr経由で配信

#### **モダンなJavaScriptフレームワーク**
- **React/Vue.js/Angular**: HTMLソースからは明確な使用は確認できていない
- **推測**: サーバーサイドレンダリング（SSR）中心の構成の可能性が高い

### CSS・スタイリング

#### **カスタムCSS**
- `/css/web/common/utilities.css` - カスタムユーティリティCSS
- **推測**: Tailwind CSS または カスタムCSSフレームワークの可能性

#### **CSSフレームワーク**
- **Bootstrap**: 使用されている可能性（一般的な求人サイトの構成）
- **Tailwind CSS**: 使用されている可能性（モダンな開発トレンド）

### テンプレートエンジン
**Blade**（Laravelのテンプレートエンジン）
- Laravelを使用しているため、Bladeテンプレートが使用されていると推測

---

## インフラストラクチャー

### Webサーバー
**Nginx**
- HTTPヘッダーで確認: `server: nginx`

### アプリケーションサーバー
**推測**: PHP-FPM または mod_php
- Laravelアプリケーションを実行するためのPHPプロセス

### クラウドサービス
**推測**: AWS または GCP
- 大規模な求人サイトとして、クラウドインフラを使用している可能性が高い

### CDN
**推測**: CloudFront または Cloudflare
- 静的ファイル（CSS、JavaScript、画像）の配信に使用されている可能性

---

## 分析ツール・サービス

### アナリティクス・タグ管理
**Google Tag Manager (GTM)**
- **ID**: `GTM-KMGBPLH` および `GTM-N75JM2`（2つのGTMコンテナが使用されている）
- **用途**: トラッキング、アナリティクス、広告配信の管理

### 広告・リマーケティング
**Google Ads**
- リマーケティングタグが実装されている
- Conversion ID: `950799708`

### その他
- **Google Analytics**: GTM経由で実装されている可能性
- **Facebook Pixel**: 実装されている可能性（確認できていない）

---

## 技術的な特徴

### アーキテクチャパターン
**サーバーサイドレンダリング（SSR）中心**
- Laravel + BladeテンプレートによるSSR
- jQueryによる軽量なクライアントサイド処理
- SPA（Single Page Application）ではなく、従来型のWebアプリケーション構成

### セキュリティ対策
- **CSRF保護**: Laravelの標準機能を使用
- **セッション管理**: セキュアなCookie設定（`secure`, `httponly`, `samesite=lax`）
- **HTTPS**: 強制されている（secureフラグから判断）

### パフォーマンス最適化
- **キャッシュ**: HTTPヘッダーに `cache-control: no-cache, private` が設定
- **アセットバージョニング**: CSSファイルにクエリパラメータ（`?1746694960`）が付与されている
- **CDN**: 静的ファイルの配信に使用されている可能性

---

## 技術スタックまとめ

### バックエンド
| 技術 | バージョン/詳細 | 確実性 |
|------|----------------|--------|
| **フレームワーク** | Laravel | ⭐⭐⭐⭐⭐（確実） |
| **言語** | PHP | ⭐⭐⭐⭐⭐（確実） |
| **Webサーバー** | Nginx | ⭐⭐⭐⭐⭐（確実） |
| **データベース** | MySQL/PostgreSQL | ⭐⭐⭐（推測） |
| **キャッシュ** | Redis/Memcached | ⭐⭐⭐（推測） |

### フロントエンド
| 技術 | バージョン/詳細 | 確実性 |
|------|----------------|--------|
| **JavaScript** | jQuery 3.7.1, 1.12.4 | ⭐⭐⭐⭐⭐（確実） |
| **スライダー** | Swiper.js 11 | ⭐⭐⭐⭐⭐（確実） |
| **CSS** | カスタムCSS | ⭐⭐⭐⭐（高確率） |
| **テンプレート** | Blade | ⭐⭐⭐⭐⭐（確実） |
| **フレームワーク** | React/Vue/Angular | ⭐（未確認） |

### インフラ・サービス
| 技術 | 詳細 | 確実性 |
|------|------|--------|
| **Webサーバー** | Nginx | ⭐⭐⭐⭐⭐（確実） |
| **クラウド** | AWS/GCP | ⭐⭐⭐（推測） |
| **CDN** | CloudFront/Cloudflare | ⭐⭐⭐（推測） |
| **アナリティクス** | Google Tag Manager | ⭐⭐⭐⭐⭐（確実） |

---

## 推測される開発構成

### 開発環境
- **バージョン管理**: Git（GitHub/GitLab）
- **CI/CD**: GitHub Actions または GitLab CI
- **コンテナ**: Docker（開発環境で使用されている可能性）

### デプロイメント
- **本番環境**: クラウド（AWS/GCP）上のNginx + PHP-FPM
- **ステージング環境**: 本番と同様の構成
- **デプロイ方法**: 自動デプロイ（CI/CDパイプライン）の可能性

---

## 確認できなかった技術

### フロントエンド
- React/Vue.js/AngularなどのモダンなJavaScriptフレームワークの使用有無
- TypeScriptの使用有無
- Webpack/Viteなどのビルドツールの使用有無

### バックエンド
- 具体的なPHPバージョン
- データベースの種類（MySQL/PostgreSQL）
- キャッシュシステムの種類（Redis/Memcached）
- キューシステム（Laravel Queue）の使用有無

### インフラ
- 具体的なクラウドプロバイダー（AWS/GCP）
- コンテナオーケストレーション（Kubernetes）の使用有無
- ロードバランサーの構成

---

## 分析の限界

この分析は以下の方法で実施しました：
1. HTTPヘッダーの確認
2. HTMLソースコードの確認
3. JavaScript/CSSファイルの確認

**注意**: 
- 実際のソースコードへのアクセスはできていないため、推測が含まれています
- より正確な分析には、実際のソースコードや技術ブログの確認が必要です
- 一部の技術は、一般的な求人サイトの構成から推測しています

---

## 参考情報

- [ドラEVER公式サイト](https://doraever.jp/)
- Laravel公式ドキュメント: https://laravel.com/docs
- Nginx公式サイト: https://nginx.org/

---

## 更新履歴
- 2025年12月19日: 初版作成

