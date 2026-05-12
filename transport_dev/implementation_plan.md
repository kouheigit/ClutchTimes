# transport_jobs 実装手順

## 前提

- 対象システム: 運送業界向け求人マッチングシステム
- 技術: Ruby on Rails 7.1 / MySQL / Devise / Pundit / Sidekiq / RSpec
- 担当範囲: 実装のみ
- 前提条件: 既存仕様に沿って進める
- 想定期間: 3〜5日

## 全体方針

実装は以下の順番で進める。

1. DB・モデル・認証まわり
2. 求職者向け機能
3. 企業向け機能
4. 管理画面・API・非同期処理
5. テスト・バグ修正・納品確認

3日対応の場合は主要機能の実装を優先する。
5日対応の場合はRSpec修正、権限確認、主要動作確認まで含める。

---

## 1日目: DB・モデル・認証まわり

まずシステムの土台を確認・実装する。

### 対象ファイル

- `Gemfile`
- `config/database.yml`
- `config/routes.rb`
- `db/migrate/20240101000001_devise_create_users.rb`
- `db/migrate/20240101000002_devise_create_companies.rb`
- `db/migrate/20240101000003_devise_create_admins.rb`
- `db/migrate/20240101000004_create_areas.rb`
- `db/migrate/20240101000005_create_license_types.rb`
- `db/migrate/20240101000006_create_jobs.rb`
- `db/migrate/20240101000007_create_applications.rb`
- `db/migrate/20240101000008_create_join_tables.rb`
- `db/seeds.rb`
- `app/models/user.rb`
- `app/models/company.rb`
- `app/models/admin.rb`
- `app/models/job.rb`
- `app/models/application.rb`
- `app/models/area.rb`
- `app/models/license_type.rb`

### 手順

1. `bundle install` を実行する。
2. `rails db:create db:migrate db:seed` を実行する。
3. Deviseの3種類ログインを確認する。
   - `User`
   - `Company`
   - `Admin`
4. `Job`, `Application`, `Area`, `LicenseType` の関連付けを確認する。
5. バリデーション、enum、scopeを実装・修正する。
6. seedで最低限の求人・地域・免許データを作成する。

### 確認ポイント

- 求人検索、応募、企業管理に必要なデータ構造が成立していること。
- `users`, `companies`, `admins` の認証が分離されていること。
- `jobs` と `applications` の関連が正しく設定されていること。

---

## 2日目: 求職者向け機能

求人検索、求人詳細、応募、応募履歴を実装する。

### 対象ファイル

- `app/controllers/jobs_controller.rb`
- `app/controllers/applications_controller.rb`
- `app/views/jobs/index.html.erb`
- `app/views/jobs/show.html.erb`
- `app/views/jobs/_job_card.html.erb`
- `app/views/applications/new.html.erb`
- `app/views/applications/index.html.erb`
- `app/views/applications/show.html.erb`
- `app/policies/job_policy.rb`
- `app/policies/application_policy.rb`

### 手順

1. `JobsController#index` で求人検索を実装する。
2. 検索条件を `Job.search` に渡す。
   - `area_id`
   - `employment_type`
   - `license_type_ids`
   - `keyword`
   - `salary_min`
3. `Job.search` のscope連携を確認する。
4. `app/views/jobs/index.html.erb` に検索フォームと求人一覧を実装する。
5. `app/views/jobs/_job_card.html.erb` で求人カード表示を整える。
6. `app/views/jobs/show.html.erb` に求人詳細と応募ボタンを実装する。
7. `ApplicationsController#new` と `ApplicationsController#create` で応募処理を実装する。
8. `current_user.applied_to?(@job)` で重複応募を防止する。
9. `ApplicationsController#index` と `ApplicationsController#show` で応募履歴を実装する。
10. `withdraw` アクションで応募取り下げを実装する。
11. Punditで本人以外の応募閲覧を防止する。

### 確認ポイント

- 未ログインユーザーは応募できないこと。
- 同じ求人に重複応募できないこと。
- 応募履歴は本人のものだけ表示されること。
- 取り下げ可能な応募だけ取り下げできること。

---

## 3日目: 企業向け機能

企業が求人を作成・編集し、応募者を確認できるようにする。

### 対象ファイル

- `app/controllers/company/base_controller.rb`
- `app/controllers/company/jobs_controller.rb`
- `app/controllers/company/applications_controller.rb`
- `app/views/layouts/company.html.erb`
- `app/views/company/jobs/index.html.erb`
- `app/views/company/jobs/new.html.erb`
- `app/views/company/jobs/edit.html.erb`
- `app/views/company/jobs/_form.html.erb`
- `app/views/company/applications/index.html.erb`
- `app/views/company/applications/show.html.erb`
- `app/policies/job_policy.rb`
- `app/policies/application_policy.rb`

### 手順

1. `Company::BaseController` で `authenticate_company!` を設定する。
2. `Company::JobsController#index` で自社求人のみ表示する。
3. `new`, `create`, `edit`, `update`, `destroy` を実装する。
4. `app/views/company/jobs/_form.html.erb` に求人入力項目を実装する。
   - `title`
   - `description`
   - `employment_type`
   - `salary_type`
   - `salary_min`
   - `salary_max`
   - `area_id`
   - `status`
   - `published_at`
   - `expires_at`
   - `license_type_ids`
5. 他社求人を編集できないように `JobPolicy` を確認する。
6. `Company::ApplicationsController#index` で自社求人への応募一覧を表示する。
7. `Company::ApplicationsController#show` で応募詳細を表示する。
8. `Company::ApplicationsController#update` で応募ステータス変更を実装する。
9. ステータス変更時の通知ワーカー呼び出しを確認する。

### 確認ポイント

- 企業は自社求人だけ閲覧・編集・削除できること。
- 他社求人にアクセスできないこと。
- 自社求人への応募だけ確認できること。
- 応募ステータス変更が保存されること。

---

## 4日目: 管理画面・API・非同期処理

管理者機能、API、Sidekiq処理を仕上げる。

### 対象ファイル

- `app/controllers/admin/base_controller.rb`
- `app/controllers/admin/dashboard_controller.rb`
- `app/controllers/admin/jobs_controller.rb`
- `app/views/layouts/admin.html.erb`
- `app/views/admin/dashboard/index.html.erb`
- `app/views/admin/jobs/index.html.erb`
- `app/policies/admin_job_policy.rb`
- `app/controllers/api/v1/base_controller.rb`
- `app/controllers/api/v1/jobs_controller.rb`
- `app/controllers/api/v1/applications_controller.rb`
- `app/services/external_job_api_client.rb`
- `app/workers/expire_jobs_worker.rb`
- `app/workers/sync_external_jobs_worker.rb`
- `app/workers/application_notification_worker.rb`
- `app/workers/status_change_notification_worker.rb`
- `config/sidekiq.yml`
- `config/initializers/sidekiq.rb`

### 手順

1. `Admin::BaseController` で `authenticate_admin!` を設定する。
2. 管理ダッシュボードに求人数・応募数などを表示する。
3. `Admin::JobsController` で求人一覧・詳細・編集・削除を実装する。
4. `AdminJobPolicy` で管理者権限を整理する。
5. `Api::V1::JobsController` の一覧・詳細・作成・更新・削除を確認する。
6. `Api::V1::ApplicationsController` の応募作成・一覧・取り下げを確認する。
7. APIのJSONレスポンスを整える。
8. `ExpireJobsWorker` で期限切れ求人を `closed` に変更する。
9. `SyncExternalJobsWorker` で外部求人API同期処理を確認する。
10. 応募作成・ステータス変更時のメール通知ワーカーを確認する。
11. `/sidekiq` の管理者アクセス制限を確認する。

### 確認ポイント

- 管理者だけ管理画面にアクセスできること。
- 管理者だけ `/sidekiq` にアクセスできること。
- APIの認証が必要な操作で正しく制限されること。
- 期限切れ求人が自動で非公開化されること。
- 外部API同期は失敗時にログと例外処理があること。

---

## 5日目: テスト・バグ修正・納品確認

RSpecと主要動作を確認し、提出可能な状態に整える。

### 対象ファイル

- `spec/models/job_spec.rb`
- `spec/models/application_spec.rb`
- `spec/models/user_spec.rb`
- `spec/controllers/company/jobs_controller_spec.rb`
- `spec/controllers/api/v1/jobs_controller_spec.rb`
- `spec/policies/job_policy_spec.rb`
- `spec/workers/expire_jobs_worker_spec.rb`
- `spec/workers/sync_external_jobs_worker_spec.rb`
- `spec/services/external_job_api_client_spec.rb`
- `spec/factories/users.rb`
- `spec/factories/companies.rb`
- `spec/factories/jobs.rb`
- `spec/factories/applications.rb`
- `spec/factories/areas.rb`
- `spec/factories/license_types.rb`

### 手順

1. `bundle exec rspec` を実行する。
2. モデルテストの失敗を修正する。
3. Controllerテストの認証・権限まわりを修正する。
4. FactoryBotの不足データを修正する。
5. Sidekiqワーカーのテストを確認する。
6. 外部API通信はWebMockでモック化する。
7. ブラウザで主要導線を確認する。
   - 求人検索
   - 求人詳細
   - 応募
   - 応募履歴
   - 応募取り下げ
8. 企業側の主要導線を確認する。
   - 企業ログイン
   - 求人作成
   - 求人編集
   - 応募者確認
   - 応募ステータス変更
9. 管理者側の主要導線を確認する。
   - 管理者ログイン
   - 求人管理
   - Sidekiq画面確認
10. READMEの起動手順どおり動くか確認する。

### 確認ポイント

- `bundle exec rspec` が通ること。
- 主要な画面遷移でエラーが出ないこと。
- 認証・認可の境界が破綻していないこと。
- seed投入後に確認しやすい初期データがあること。

---

## 3日対応に短縮する場合

3日で進める場合は以下を優先する。

### 1日目

- DB・モデル・認証
- 求人一覧・詳細
- 求人検索

### 2日目

- 応募機能
- 応募履歴
- 応募取り下げ
- 企業側の求人CRUD

### 3日目

- 企業側の応募管理
- 管理画面の最低限
- APIの主要部分
- RSpecの主要失敗修正
- 主要導線の動作確認

### 3日対応で後回しにする可能性があるもの

- 管理画面の細かいUI調整
- APIレスポンスの細かい整形
- 外部API実接続検証
- Sidekiqスケジュールの本番運用確認
- メール文面の細かい調整
- テストカバレッジの拡充

---

## 相手への説明文

実装のみの担当で、既存仕様に沿って進める前提であれば、3〜5日程度で対応可能です。

実装は、DB・モデル、求職者機能、企業機能、管理画面/API/Sidekiq、テスト修正の順で進めます。
主な対象ファイルは `app/models`、`app/controllers`、`app/views`、`app/policies`、`app/workers`、`app/services`、`spec` 配下です。

3日対応の場合は主要機能優先、5日対応の場合はRSpec修正と主要動作確認まで含めて進めます。

ただし、仕様追加、本番環境構築、外部APIの実接続検証は別途調整が必要です。
