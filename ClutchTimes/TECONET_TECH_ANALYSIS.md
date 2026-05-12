# teconet.co.jp 技術スタック詳細分析

## 分析日
2025年12月22日

## サイト概要
テコネット株式会社のコーポレートサイト。システムインテグレーション、モバイルアプリ開発、Webアプリ開発などのサービスを提供する企業の公式サイト。

---

## バックエンド技術スタック

### サーバーサイドフレームワーク
**Laravel (PHP)** - **確実**

**根拠**:
1. **CSRFトークン**: `<meta name="csrf-token">` がHTMLに含まれている
   - Laravelの標準的なCSRF保護機能
   - トークン名がLaravelのデフォルト形式

2. **セッション管理**: HTTPヘッダーに以下のCookieが設定されている
   - `XSRF-TOKEN` - Laravelの標準CSRFトークン名
   - `laravel-session` - LaravelのセッションCookie名

3. **サーバー**: `nginx` が使用されている（Laravelの一般的な構成）

### プログラミング言語
**PHP**（Laravelフレームワーク使用）

### データベース
**推測**: MySQL または PostgreSQL
- コーポレートサイトとして、お問い合わせフォームなどのデータを扱う可能性

---

## フロントエンド技術スタック

### JavaScriptフレームワーク・ビルドツール

#### **Vite** または **Webpack**（推測）
- **根拠**: 
  - ES Modules (`type="module"`) が使用されている
  - ビルドされたアセットファイル（`app-B7kaNtdY.js`, `app-CoLqYjFA.css`）
  - ハッシュ付きファイル名（`app-B7kaNtdY.js`）によるキャッシュバスティング
  - `modulepreload` によるモジュールのプリロード

#### **モダンなJavaScript**
- ES Modules形式で実装
- モジュールバンドリングによる最適化

### CSSフレームワーク

#### **Tailwind CSS** - **確実**
- **根拠**: HTMLクラス名から確認
  - `bg-gradient-to-r` - Tailwindのグラデーションクラス
  - `text-6xl`, `md:text-[120px]`, `lg:text-[180px]` - Tailwindのレスポンシブタイポグラフィ
  - `container`, `mx-auto`, `px-4`, `grid`, `grid-cols-2` - Tailwindのユーティリティクラス
  - `from-[#0051b1]`, `via-[#62b2f7]`, `to-white` - Tailwindのカスタムカラー指定

#### **Font Awesome 6.7.2**
- **用途**: アイコン表示
- **CDN**: Cloudflare CDN経由で配信
- **確認**: `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">`

### アニメーション

#### **SVGアニメーション**
- **用途**: ロゴやイラストのアニメーション
- **技術**: SVG `<animate>` 要素を使用
- **特徴**: 
  - フェードインアニメーション（`opacity`）
  - スライドアニメーション（`translate`）
  - イージング関数（`keySplines`）による滑らかな動き

#### **CSSアニメーション**
- **用途**: スクロール時のフェードイン効果（`fade-up`クラス）

---

## インフラストラクチャー

### Webサーバー
**Nginx**
- HTTPヘッダーで確認: `server: nginx`

### ホスティングプラットフォーム
**KUSANAGI** - **確実**
- **根拠**: HTTPヘッダーに `x-signature-wexal: KUSANAGI` が含まれている
- **特徴**: 
  - WordPress/Laravel向けの高速化プラットフォーム
  - Nginx + PHP-FPM + Redis/Memcachedの構成
  - 日本製のホスティングプラットフォーム

### キャッシュシステム

#### **Nginxキャッシュ**
- **確認**: `x-pst-nginx-cache: MISS` / `HIT`
- **用途**: 静的ファイルやページのキャッシュ

#### **Page Speed Tool (PST)**
- **バージョン**: 3.1.29（`x-pst-version: 3.1.29`）
- **用途**: ページ速度最適化
- **機能**: 
  - 動的コンテンツのキャッシュ
  - レスポンス時間の最適化（`x-pst-dynamic: HIT; 0.689 ms`）

#### **Bキャッシュ**
- **確認**: `x-b-cache: B=nil:D=HIT`
- **用途**: データベースクエリのキャッシュ

### CDN
**Cloudflare**（推測）
- Font AwesomeがCloudflare CDN経由で配信されている
- DNSプリフェッチ（`dns-prefetch`）が設定されている

---

## セキュリティ対策

### Content Security Policy (CSP)
```
default-src 'self'; 
script-src 'self'; 
style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; 
img-src 'self' data: blob:; 
font-src 'self' data: https://cdnjs.cloudflare.com; 
object-src 'none'; 
base-uri 'self'; 
frame-ancestors 'self'; 
form-action 'self'; 
connect-src 'self'; 
upgrade-insecure-requests
```

**特徴**:
- スクリプトは同一オリジンのみ許可
- スタイルは同一オリジンとCloudflare CDNを許可
- 画像は同一オリジン、data URI、blob URIを許可
- インラインスクリプトは制限されている

### その他のセキュリティヘッダー
- **X-XSS-Protection**: `1; mode=block` - XSS攻撃の防止
- **X-Content-Type-Options**: `nosniff` - MIMEタイプスニッフィングの防止
- **X-Frame-Options**: `SAMEORIGIN` - クリックジャッキング対策
- **Referrer-Policy**: `strict-origin-when-cross-origin` - リファラー情報の制御
- **Permissions-Policy**: `camera=(), microphone=(), geolocation=()` - 権限の制限
- **Cross-Origin-Opener-Policy**: `same-origin` - クロスオリジン分離
- **Cross-Origin-Resource-Policy**: `same-site` - クロスオリジンリソースポリシー

---

## パフォーマンス最適化

### アセット最適化
- **ハッシュ付きファイル名**: `app-B7kaNtdY.js`, `app-CoLqYjFA.css`
  - キャッシュバスティングによる効率的なキャッシュ管理
- **モジュールプリロード**: `<link rel="modulepreload">`
  - JavaScriptモジュールの早期読み込み
- **スタイルプリロード**: `<link rel="preload" as="style">`
  - CSSの早期読み込み

### リソースヒント
- **DNSプリフェッチ**: `dns-prefetch` - DNS解決の事前実行
- **プリコネクト**: `preconnect` - 接続の事前確立

### キャッシュ戦略
- **Nginxキャッシュ**: 静的ファイルとページのキャッシュ
- **PSTキャッシュ**: 動的コンテンツのキャッシュ
- **Bキャッシュ**: データベースクエリのキャッシュ

---

## 技術的な特徴

### アーキテクチャパターン
**サーバーサイドレンダリング（SSR）中心**
- Laravel + BladeテンプレートによるSSR
- モダンなJavaScript（ES Modules）による軽量なクライアントサイド処理
- Tailwind CSSによるユーティリティファーストのスタイリング

### レスポンシブデザイン
- **Tailwind CSS**によるモバイルファーストデザイン
- ブレークポイント: `sm:`, `md:`, `lg:`, `xl:`, `2xl:`
- グリッドシステム: `grid`, `grid-cols-2`, `md:grid-cols-3`, `lg:grid-cols-4`

### アニメーション・インタラクション
- **SVGアニメーション**: ロゴやイラストのアニメーション
- **CSSアニメーション**: スクロール時のフェードイン効果
- **滑らかなイージング**: `keySplines`による自然な動き

---

## 技術スタックまとめ

### バックエンド
| 技術 | バージョン/詳細 | 確実性 |
|------|----------------|--------|
| **フレームワーク** | Laravel | ⭐⭐⭐⭐⭐（確実） |
| **言語** | PHP | ⭐⭐⭐⭐⭐（確実） |
| **Webサーバー** | Nginx | ⭐⭐⭐⭐⭐（確実） |
| **データベース** | MySQL/PostgreSQL | ⭐⭐⭐（推測） |

### フロントエンド
| 技術 | バージョン/詳細 | 確実性 |
|------|----------------|--------|
| **CSSフレームワーク** | Tailwind CSS | ⭐⭐⭐⭐⭐（確実） |
| **アイコン** | Font Awesome 6.7.2 | ⭐⭐⭐⭐⭐（確実） |
| **JavaScript** | ES Modules | ⭐⭐⭐⭐⭐（確実） |
| **ビルドツール** | Vite/Webpack | ⭐⭐⭐⭐（高確率） |
| **アニメーション** | SVG, CSS | ⭐⭐⭐⭐⭐（確実） |

### インフラ・サービス
| 技術 | 詳細 | 確実性 |
|------|------|--------|
| **ホスティング** | KUSANAGI | ⭐⭐⭐⭐⭐（確実） |
| **Webサーバー** | Nginx | ⭐⭐⭐⭐⭐（確実） |
| **キャッシュ** | Nginx Cache, PST, B Cache | ⭐⭐⭐⭐⭐（確実） |
| **CDN** | Cloudflare | ⭐⭐⭐⭐（高確率） |

---

## 開発環境の推測

### ビルドツール
- **Vite** または **Webpack** + **Laravel Mix**
- ES Modules形式での開発
- ホットリロード機能

### バージョン管理
- **Git**（GitHub/GitLab）

### デプロイメント
- **KUSANAGI**プラットフォーム経由
- 自動デプロイの可能性

---

## パフォーマンス指標

### レスポンス時間
- **PST動的キャッシュ**: 0.689 ms（非常に高速）

### キャッシュヒット率
- Nginxキャッシュ、PSTキャッシュ、Bキャッシュが適切に設定されている

---

## セキュリティ評価

### 強み
- **包括的なCSP設定**: XSS攻撃に対する強力な防御
- **複数のセキュリティヘッダー**: 多層的なセキュリティ対策
- **HTTPS強制**: `upgrade-insecure-requests` によりHTTPSを強制

### 推奨事項
- CSPの`unsafe-inline`を削除（現在はスタイルに使用されている）
- 定期的なセキュリティ監査の実施

---

## まとめ

teconet.co.jpは、**Laravel + Tailwind CSS + KUSANAGI**というモダンで高性能な技術スタックを使用したコーポレートサイトです。

### 主な特徴
1. **モダンなフロントエンド**: Tailwind CSS + ES Modules
2. **高性能なインフラ**: KUSANAGIによる最適化
3. **強力なセキュリティ**: 包括的なCSPとセキュリティヘッダー
4. **優れたパフォーマンス**: 多層的なキャッシュシステム
5. **美しいUI/UX**: SVGアニメーションとレスポンシブデザイン

### 技術的な評価
- **開発効率**: ⭐⭐⭐⭐⭐（Tailwind CSSによる高速開発）
- **パフォーマンス**: ⭐⭐⭐⭐⭐（KUSANAGIによる最適化）
- **セキュリティ**: ⭐⭐⭐⭐⭐（包括的な対策）
- **保守性**: ⭐⭐⭐⭐（モダンな技術スタック）

---

## 参考情報

- [teconet.co.jp](https://teconet.co.jp/)
- [KUSANAGI公式サイト](https://kusanagi.tokyo/)
- [Tailwind CSS公式サイト](https://tailwindcss.com/)
- [Laravel公式ドキュメント](https://laravel.com/docs)

---

## 更新履歴
- 2025年12月22日: 初版作成

