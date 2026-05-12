# ドラEVERをRailsに置き換えた場合の技術スタック

## 概要
現在のLaravel（PHP）ベースのシステムをRuby on Railsに置き換えた場合の技術構成と実装方法を説明します。

---

## バックエンド技術スタック

### フレームワーク
**Ruby on Rails 7.x**
- **バージョン**: Rails 7.1以上（最新の機能とパフォーマンス改善）
- **特徴**: 
  - Convention over Configuration（設定より規約）
  - DRY原則（Don't Repeat Yourself）
  - RESTfulな設計思想

### プログラミング言語
**Ruby 3.x**
- **バージョン**: Ruby 3.2以上
- **特徴**: 
  - オブジェクト指向プログラミング
  - メタプログラミング機能
  - 豊富なGemエコシステム

### データベース
**PostgreSQL**（推奨）または **MySQL**
- **理由**: 
  - RailsのデフォルトはSQLite（開発環境）、本番はPostgreSQL推奨
  - 大量の求人データを扱うため、PostgreSQLが適している
  - 全文検索機能（pg_search）が利用可能

### キャッシュ
**Redis**
- **用途**: 
  - セッション管理
  - フラグメントキャッシュ
  - バックグラウンドジョブ（Sidekiq）のキュー管理

### バックグラウンドジョブ
**Sidekiq**
- **用途**: 
  - メール送信
  - データ集計処理
  - 外部API連携
  - バッチ処理

---

## フロントエンド技術スタック

### JavaScriptライブラリ
**jQuery 3.7.1**（継続使用）
- **理由**: 既存のコード資産を活用
- **導入方法**: 
  - `yarn add jquery` または `gem 'jquery-rails'`
  - WebpackerまたはImportmap経由で読み込み

**Swiper.js 11**（継続使用）
- **導入方法**: 
  - `yarn add swiper` または CDN経由
  - JavaScriptバンドラー経由で読み込み

### CSS・スタイリング

#### **オプション1: Tailwind CSS**（推奨）
```ruby
# Gemfile
gem 'tailwindcss-rails'
```
- **特徴**: ユーティリティファーストのCSSフレームワーク
- **利点**: モダンな開発体験、カスタマイズが容易

#### **オプション2: Bootstrap 5**
```ruby
# Gemfile
gem 'bootstrap', '~> 5.3'
```
- **特徴**: 既存のBootstrap資産を活用可能
- **利点**: 豊富なコンポーネント、ドキュメントが充実

#### **オプション3: カスタムCSS**
- Sass/SCSSを使用したカスタムスタイリング
- Railsのアセットパイプラインで管理

### テンプレートエンジン
**ERB (Embedded Ruby)**
- **特徴**: Railsのデフォルトテンプレートエンジン
- **構文**: `<% %>` と `<%= %>`
- **パーシャル**: `_partial.html.erb` 形式

### JavaScriptバンドラー

#### **オプション1: Importmap**（Rails 7のデフォルト）
```ruby
# config/importmap.rb
pin "jquery", to: "https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"
pin "swiper", to: "https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"
```
- **特徴**: ビルドステップ不要、ES modulesを直接使用
- **利点**: シンプル、高速

#### **オプション2: Webpacker**（従来型）
```ruby
# Gemfile
gem 'webpacker', '~> 6.0'
```
- **特徴**: Webpackベースのアセット管理
- **利点**: 複雑なビルドプロセスに対応

#### **オプション3: jsbundling-rails + esbuild**
```ruby
# Gemfile
gem 'jsbundling-rails'
```
- **特徴**: モダンなJavaScriptバンドラー
- **利点**: 高速なビルド、Tree shaking対応

---

## インフラストラクチャー

### Webサーバー
**Nginx**（継続使用）
- **理由**: リバースプロキシ、静的ファイル配信

### アプリケーションサーバー

#### **オプション1: Puma**（推奨）
```ruby
# Gemfile
gem 'puma', '~> 6.0'
```
- **特徴**: Railsのデフォルトアプリケーションサーバー
- **利点**: マルチスレッド対応、高パフォーマンス

#### **オプション2: Unicorn**
- **特徴**: マルチプロセス型
- **利点**: 安定性重視

### デプロイメント

#### **オプション1: Capistrano**
```ruby
# Gemfile
gem 'capistrano', '~> 3.17'
gem 'capistrano-rails'
gem 'capistrano-rbenv'
gem 'capistrano3-puma'
```
- **特徴**: 自動デプロイツール
- **利点**: ロールバック機能、複数サーバー対応

#### **オプション2: Docker + Kubernetes**
```dockerfile
# Dockerfile
FROM ruby:3.2
WORKDIR /app
COPY Gemfile Gemfile.lock ./
RUN bundle install
COPY . .
CMD ["rails", "server", "-b", "0.0.0.0"]
```
- **特徴**: コンテナベースのデプロイ
- **利点**: 環境の統一、スケーラビリティ

#### **オプション3: Heroku / Render / Fly.io**
- **特徴**: マネージドサービス
- **利点**: 簡単なデプロイ、自動スケーリング

### クラウドサービス
**AWS** または **GCP**
- **推奨構成**:
  - EC2/Compute Engine: アプリケーションサーバー
  - RDS/Cloud SQL: データベース
  - ElastiCache/Cloud Memorystore: Redis
  - S3/Cloud Storage: 静的ファイル・画像保存
  - CloudFront/Cloud CDN: CDN

---

## 主要なGem（ライブラリ）

### 認証・認可
```ruby
# 認証
gem 'devise'                    # ユーザー認証
gem 'devise-i18n'              # 多言語対応
gem 'omniauth'                 # OAuth認証
gem 'omniauth-google-oauth2'   # Google認証

# 認可
gem 'pundit'                   # 権限管理
gem 'cancancan'                # 権限管理（別選択肢）
```

### データベース関連
```ruby
gem 'pg'                       # PostgreSQLアダプター
gem 'pg_search'                # 全文検索
gem 'kaminari'                 # ページネーション
gem 'ransack'                  # 検索機能
```

### API・JSON
```ruby
gem 'jbuilder'                 # JSONビルダー
gem 'active_model_serializers' # JSONシリアライザー
gem 'fast_jsonapi'             # 高速JSON API
```

### バックグラウンドジョブ
```ruby
gem 'sidekiq'                  # バックグラウンドジョブ
gem 'sidekiq-cron'             # スケジュールジョブ
gem 'whenever'                 # Cronジョブ管理
```

### キャッシュ
```ruby
gem 'redis-rails'              # Redis統合
gem 'dalli'                    # Memcachedクライアント
```

### メール送信
```ruby
gem 'mailgun-ruby'             # Mailgun
gem 'sendgrid-ruby'            # SendGrid
gem 'letter_opener'            # 開発環境用メールプレビュー
```

### 画像処理
```ruby
gem 'image_processing'          # 画像リサイズ
gem 'mini_magick'              # ImageMagickラッパー
gem 'aws-sdk-s3'               # S3アップロード
```

### セキュリティ
```ruby
gem 'rack-attack'              # レート制限・DDoS対策
gem 'brakeman'                 # セキュリティスキャン
```

### テスト
```ruby
gem 'rspec-rails'              # テストフレームワーク
gem 'factory_bot_rails'        # テストデータ生成
gem 'faker'                    # ダミーデータ生成
gem 'capybara'                 # 統合テスト
gem 'vcr'                      # HTTPリクエストの記録・再生
```

### 開発ツール
```ruby
gem 'pry-rails'                # デバッガー
gem 'bullet'                   # N+1問題検出
gem 'annotate'                 # モデルにスキーマ情報を追加
gem 'rubocop'                  # コードスタイルチェック
```

---

## プロジェクト構造

### Railsアプリケーション構造
```
doraever/
├── app/
│   ├── controllers/
│   │   ├── application_controller.rb
│   │   ├── jobs_controller.rb          # 求人一覧・詳細
│   │   ├── companies_controller.rb     # 企業情報
│   │   ├── users_controller.rb         # ユーザー管理
│   │   └── admin/
│   │       └── jobs_controller.rb      # 管理者用求人管理
│   ├── models/
│   │   ├── job.rb                      # 求人モデル
│   │   ├── company.rb                  # 企業モデル
│   │   ├── user.rb                     # ユーザーモデル
│   │   └── application_record.rb
│   ├── views/
│   │   ├── layouts/
│   │   │   └── application.html.erb
│   │   ├── jobs/
│   │   │   ├── index.html.erb          # 求人一覧
│   │   │   └── show.html.erb           # 求人詳細
│   │   └── companies/
│   │       └── show.html.erb
│   ├── helpers/                        # ビューヘルパー
│   ├── mailers/                        # メーラー
│   ├── jobs/                           # バックグラウンドジョブ
│   └── assets/
│       ├── stylesheets/
│       │   └── application.css
│       └── javascripts/
│           └── application.js
├── config/
│   ├── routes.rb                       # ルーティング
│   ├── database.yml                    # DB設定
│   ├── application.rb                 # アプリケーション設定
│   └── environments/
│       ├── development.rb
│       ├── production.rb
│       └── test.rb
├── db/
│   ├── migrate/                        # マイグレーション
│   └── seeds.rb                        # シードデータ
├── lib/
│   └── tasks/                          # Rakeタスク
├── spec/                               # テスト（RSpec使用時）
├── Gemfile                             # 依存関係
└── config.ru                           # Rack設定
```

---

## LaravelからRailsへの移行マッピング

### コントローラー
| Laravel | Rails |
|---------|-------|
| `App\Http\Controllers\Controller` | `ApplicationController` |
| `public function index()` | `def index` |
| `return view('admin.home')` | `render 'admin/home'` |
| `$request->input('title')` | `params[:title]` |
| `DB::table('jobs')->get()` | `Job.all` |

### モデル
| Laravel | Rails |
|---------|-------|
| `class Job extends Model` | `class Job < ApplicationRecord` |
| `protected $fillable` | 不要（デフォルトで許可） |
| `Job::where('status', 'active')->get()` | `Job.where(status: 'active')` |
| `$job->save()` | `job.save` |
| `DB::table('jobs')->insert()` | `Job.create()` |

### ルーティング
| Laravel | Rails |
|---------|-------|
| `Route::get('/jobs', [JobController::class, 'index'])` | `get '/jobs', to: 'jobs#index'` |
| `Route::group(['prefix' => 'admin'], function() {})` | `namespace :admin do` |
| `Route::resource('jobs', JobController::class)` | `resources :jobs` |

### ビュー
| Laravel | Rails |
|---------|-------|
| `@extends('layouts.app')` | `<%= render 'layouts/application' %>` |
| `@section('content')` | `<% content_for :content do %>` |
| `{{ $title }}` | `<%= @title %>` |
| `@if($condition)` | `<% if @condition %>` |
| `@foreach($items as $item)` | `<% @items.each do \|item\| %>` |

### 認証
| Laravel | Rails |
|---------|-------|
| `Auth::user()` | `current_user` |
| `auth()->check()` | `user_signed_in?` |
| `Auth::guard('admin')` | `authenticate_admin!` (Devise) |
| `$this->middleware('auth')` | `before_action :authenticate_user!` |

### セッション
| Laravel | Rails |
|---------|-------|
| `session(['key' => 'value'])` | `session[:key] = 'value'` |
| `$request->session()->get('key')` | `session[:key]` |
| `session()->flash('message')` | `flash[:notice] = 'message'` |

### バリデーション
| Laravel | Rails |
|---------|-------|
| `$request->validate([...])` | `validates :title, presence: true` |
| `FormRequest` | モデル内の`validates`または`ActiveModel::Model` |

---

## 実装例

### コントローラー例
```ruby
# app/controllers/jobs_controller.rb
class JobsController < ApplicationController
  before_action :authenticate_user!, except: [:index, :show]
  
  def index
    @jobs = Job.includes(:company)
                .where(status: 'active')
                .order(created_at: :desc)
                .page(params[:page])
    
    @jobs = @jobs.search(params[:q]) if params[:q].present?
  end
  
  def show
    @job = Job.find(params[:id])
    @company = @job.company
  end
  
  private
  
  def job_params
    params.require(:job).permit(:title, :description, :salary, :location)
  end
end
```

### モデル例
```ruby
# app/models/job.rb
class Job < ApplicationRecord
  belongs_to :company
  has_many :applications
  has_many :users, through: :applications
  
  validates :title, presence: true, length: { maximum: 255 }
  validates :description, presence: true
  validates :salary, presence: true, numericality: { greater_than: 0 }
  
  scope :active, -> { where(status: 'active') }
  scope :recent, -> { order(created_at: :desc) }
  
  # 全文検索（pg_search使用時）
  include PgSearch::Model
  pg_search_scope :search,
    against: [:title, :description],
    using: {
      tsearch: { prefix: true }
    }
end
```

### ルーティング例
```ruby
# config/routes.rb
Rails.application.routes.draw do
  root 'jobs#index'
  
  devise_for :users
  devise_for :admins, path: 'admin', controllers: {
    sessions: 'admin/sessions'
  }
  
  resources :jobs, only: [:index, :show] do
    resources :applications, only: [:create]
  end
  
  namespace :admin do
    root 'dashboard#index'
    resources :jobs
    resources :companies
    resources :users
  end
end
```

### ビュー例
```erb
<!-- app/views/jobs/index.html.erb -->
<div class="container">
  <h1>求人一覧</h1>
  
  <%= form_with url: jobs_path, method: :get, local: true do |f| %>
    <%= f.text_field :q, placeholder: "検索..." %>
    <%= f.submit "検索" %>
  <% end %>
  
  <div class="jobs-list">
    <% @jobs.each do |job| %>
      <div class="job-card">
        <h2><%= link_to job.title, job_path(job) %></h2>
        <p><%= job.company.name %></p>
        <p><%= number_to_currency(job.salary) %></p>
        <p><%= job.location %></p>
      </div>
    <% end %>
  </div>
  
  <%= paginate @jobs %>
</div>
```

---

## パフォーマンス最適化

### データベース
```ruby
# N+1問題の解決
@jobs = Job.includes(:company, :applications).all

# インデックス追加
add_index :jobs, :status
add_index :jobs, [:status, :created_at]
add_index :jobs, :company_id

# クエリ最適化
Job.where(status: 'active')
   .select(:id, :title, :company_id)
   .limit(20)
```

### キャッシュ
```ruby
# フラグメントキャッシュ
<% cache @job do %>
  <%= render @job %>
<% end %>

# 低レベルキャッシュ
Rails.cache.fetch("jobs/#{params[:page]}", expires_in: 1.hour) do
  Job.active.page(params[:page]).to_a
end

# キャッシュキー
Rails.cache.fetch("job_#{@job.id}_#{@job.updated_at.to_i}") do
  @job.to_json
end
```

### バックグラウンドジョブ
```ruby
# app/jobs/application_notification_job.rb
class ApplicationNotificationJob < ApplicationJob
  queue_as :default
  
  def perform(application_id)
    application = Application.find(application_id)
    ApplicationMailer.notify_new_application(application).deliver_now
  end
end

# コントローラーから呼び出し
ApplicationNotificationJob.perform_later(@application.id)
```

---

## セキュリティ対策

### CSRF保護
```ruby
# RailsはデフォルトでCSRF保護が有効
# app/controllers/application_controller.rb
class ApplicationController < ActionController::Base
  protect_from_forgery with: :exception
end
```

### SQLインジェクション対策
```ruby
# RailsのORMは自動的にエスケープ
Job.where("title LIKE ?", "%#{params[:q]}%")  # 安全
Job.where("title LIKE '%#{params[:q]}%'")    # 危険（使用しない）
```

### XSS対策
```erb
<!-- 自動エスケープ（デフォルト） -->
<%= @job.title %>  <!-- 安全 -->

<!-- エスケープを無効にする場合（注意が必要） -->
<%= raw @job.description %>
<%= @job.description.html_safe %>
```

### 認証・認可
```ruby
# Deviseによる認証
before_action :authenticate_user!

# Punditによる認可
def show
  @job = Job.find(params[:id])
  authorize @job
end
```

---

## デプロイメント構成例

### Capistrano設定
```ruby
# config/deploy.rb
set :application, 'doraever'
set :repo_url, 'git@github.com:company/doraever.git'
set :deploy_to, '/var/www/doraever'
set :linked_files, %w[config/database.yml config/secrets.yml]
set :linked_dirs, %w[log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system]
set :rbenv_ruby, '3.2.0'
set :puma_bind, "unix://#{shared_path}/tmp/sockets/puma.sock"
```

### Nginx設定
```nginx
upstream puma {
  server unix:///var/www/doraever/shared/tmp/sockets/puma.sock;
}

server {
  listen 80;
  server_name doraever.jp;
  root /var/www/doraever/current/public;
  
  location / {
    try_files $uri @puma;
  }
  
  location @puma {
    proxy_pass http://puma;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
  }
  
  location ~ ^/assets/ {
    expires 1y;
    add_header Cache-Control public;
  }
}
```

---

## 移行時の注意点

### 1. データベース移行
- LaravelのマイグレーションをRailsのマイグレーションに変換
- データのエクスポート・インポートが必要
- 外部キー制約の確認

### 2. セッション管理
- Laravelのセッション形式とRailsのセッション形式が異なる
- 既存ユーザーのセッションを無効化する必要がある可能性

### 3. 認証システム
- Laravelの認証をDeviseに移行
- パスワードハッシュの互換性確認（bcryptは両方で使用可能）

### 4. ルーティング
- URL構造を維持するか、リダイレクトを設定するか検討
- SEOへの影響を考慮

### 5. テンプレート
- BladeテンプレートをERBに変換
- ヘルパーメソッドの違いを確認

### 6. アセット管理
- Laravel MixからRailsのアセットパイプラインに移行
- 静的ファイルのパス変更

---

## まとめ

### Rails版の利点
1. **開発効率**: Convention over Configurationにより、コード量が削減
2. **Gemエコシステム**: 豊富なライブラリが利用可能
3. **テスト**: RSpecによる充実したテスト機能
4. **スケーラビリティ**: 大規模アプリケーションにも対応
5. **コミュニティ**: 活発なコミュニティと豊富なリソース

### 移行の推奨手順
1. **環境構築**: Rails開発環境のセットアップ
2. **データベース設計**: スキーマの移行
3. **モデル実装**: ActiveRecordモデルの作成
4. **コントローラー実装**: ビジネスロジックの移行
5. **ビュー実装**: テンプレートの変換
6. **テスト実装**: RSpecによるテスト作成
7. **デプロイ**: 本番環境への移行

---

## 参考リソース

- [Ruby on Rails公式ドキュメント](https://guides.rubyonrails.org/)
- [Devise公式ドキュメント](https://github.com/heartcombo/devise)
- [Sidekiq公式ドキュメント](https://github.com/sidekiq/sidekiq)
- [RSpec公式ドキュメント](https://rspec.info/)

