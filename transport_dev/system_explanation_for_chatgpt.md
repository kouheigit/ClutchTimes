# 運送業界向け求人マッチングシステム 技術説明

このドキュメントは、この Rails プロジェクトの目的、技術構成、DB 設計、モデル設計、認証方式、求人検索、応募管理、今後実装予定の機能を ChatGPT などに説明するための資料です。

## 1. システム概要

このシステムは、運送業界向けの求人マッチングサービスです。

主な利用者は以下の 3 種類です。

- 求職者 `User`
- 求人掲載企業 `Company`
- 管理者 `Admin`

求職者は求人を検索し、気になる求人に応募できます。企業は自社の求人を作成・管理し、求人に対する応募を確認できます。管理者はサービス全体の求人、応募、企業、ユーザーを管理する想定です。

現在の実装フェーズでは、まず DB、モデル、Devise 認証、求人検索、応募重複防止など、アプリケーションの土台を作っています。

## 2. 技術スタック

アプリ本体はリポジトリ直下ではなく `src/` 配下にあります。

- フレームワーク: Ruby on Rails 7.1
- Ruby: 3.2.11
- DB: MySQL
- 認証: Devise
- 権限管理予定: Pundit
- 非同期処理予定: Sidekiq
- テスト: RSpec, FactoryBot, Shoulda Matchers
- ページネーション予定: Kaminari
- フロントエンド基盤: Hotwire, Turbo, Stimulus, importmap

Rails 標準パスは `src/` から見たパスとして扱います。

例:

- Rails アプリルート: `src/`
- モデル: `src/app/models`
- ルーティング: `src/config/routes.rb`
- DB migration: `src/db/migrate`
- schema: `src/db/schema.rb`
- spec: `src/spec`

## 3. 認証設計

このシステムでは、求職者、企業、管理者を同じ users テーブルに混ぜず、Devise のモデルを 3 つに分けています。

```ruby
devise_for :admins
devise_for :companies
devise_for :users
```

ログイン URL は以下のように分かれます。

- 求職者: `/users/sign_in`
- 企業: `/companies/sign_in`
- 管理者: `/admins/sign_in`

この設計により、利用者種別ごとの責務を DB レベルでも Rails の認証スコープでも分離できます。

### User

求職者を表します。

主な役割:

- 求人への応募
- 応募履歴の保持
- 同じ求人に応募済みかどうかの判定

関連:

```ruby
has_many :applications, dependent: :destroy
has_many :applied_jobs, through: :applications, source: :job
```

`applied_to?(job)` により、求職者が特定の求人に対して有効な応募を持っているかを判定します。取り下げ済み `withdrawn` の応募は有効応募として扱いません。

### Company

求人掲載企業を表します。

主な役割:

- 自社求人の所有
- 求人作成・編集・削除
- 自社求人に対する応募確認

関連:

```ruby
has_many :jobs, dependent: :destroy
```

企業が削除された場合、その企業が持つ求人も削除される設計です。

### Admin

管理者を表します。

主な役割:

- 管理画面へのログイン
- 求人、応募、ユーザー、企業の管理
- 将来的には Sidekiq 管理画面へのアクセス制御

## 4. DB 設計

主要テーブルは以下です。

| テーブル | 役割 |
| --- | --- |
| `users` | 求職者アカウント |
| `companies` | 企業アカウント |
| `admins` | 管理者アカウント |
| `jobs` | 求人 |
| `applications` | 求職者から求人への応募 |
| `areas` | 勤務地エリア |
| `license_types` | 運送業務に必要な免許種別 |
| `jobs_license_types` | 求人と免許種別の中間テーブル |

## 5. ER 図イメージ

```text
User
  1 ── * Application * ── 1 Job

Company
  1 ── * Job

Area
  1 ── * Job

Job
  * ── * LicenseType
      through jobs_license_types
```

関係性を文章で説明すると、1 人の求職者は複数の応募を持ちます。1 つの応募は 1 人の求職者と 1 つの求人に紐づきます。1 つの企業は複数の求人を持ちます。1 つの求人は 1 つのエリアに属します。求人と免許種別は多対多で、1 つの求人に複数の必要免許を設定できます。

## 6. jobs テーブル

`jobs` は求人情報を保存する中心テーブルです。

主なカラム:

| カラム | 型 | 説明 |
| --- | --- | --- |
| `company_id` | bigint | 求人を掲載した企業 |
| `title` | string | 求人タイトル |
| `description` | text | 求人詳細 |
| `employment_type` | integer | 雇用形態 enum |
| `salary_type` | integer | 給与種別 enum |
| `salary_min` | integer | 最低給与 |
| `salary_max` | integer | 最高給与 |
| `area_id` | bigint | 勤務地エリア |
| `status` | integer | 求人ステータス enum |
| `published_at` | datetime | 公開日時 |
| `expires_at` | datetime | 掲載期限 |

### Job の enum 設計

求人には以下の enum を持たせる想定です。

```ruby
enum :employment_type, {
  full_time: 0,
  part_time: 1,
  contract: 2,
  temporary: 3
}

enum :salary_type, {
  monthly: 0,
  hourly: 1,
  annual: 2
}

enum :status, {
  draft: 0,
  published: 1,
  closed: 2
}, scopes: false
```

`status` に `published` があるため、Rails enum は自動で `published` scope を作ろうとします。しかしこのシステムでは手書きで `published` scope を定義したいため、`scopes: false` を付ける設計が安全です。

## 7. 求人検索設計

求人検索は `Job.search(params)` に集約する想定です。

検索条件:

- `area_id`
- `employment_type`
- `salary_min`
- `license_type_ids`
- `keyword`

期待する検索仕様:

```ruby
Job.search(
  area_id: 1,
  employment_type: "full_time",
  salary_min: 250000,
  license_type_ids: [1, 2],
  keyword: "大型"
)
```

### scope の役割

```ruby
scope :published, -> { where(status: statuses[:published]) }
scope :active, -> { published.where("expires_at IS NULL OR expires_at > ?", Time.current) }
scope :expired, -> { published.where("expires_at <= ?", Time.current) }
```

- `published`: 公開中ステータスの求人を返す
- `active`: 公開中かつ期限切れでない求人を返す
- `expired`: 公開中だが期限切れの求人を返す

### license_type_ids 検索

求人と免許種別は多対多です。そのため免許種別で検索する場合は `joins(:license_types)` を使います。

```ruby
jobs = jobs
  .joins(:license_types)
  .where(license_types: { id: params[:license_type_ids] })
  .distinct
```

`distinct` を付ける理由は、1 つの求人が複数の免許種別に一致した場合、SQL の JOIN 結果として同じ求人が複数行返る可能性があるためです。

### keyword 検索

キーワード検索では `title` と `description` を対象にします。

```ruby
keyword = "%#{sanitize_sql_like(params[:keyword])}%"
jobs = jobs.where("title LIKE ? OR description LIKE ?", keyword, keyword)
```

`sanitize_sql_like` を使うと、検索語に `%` や `_` が含まれていても LIKE のワイルドカードとして暴発しにくくなります。

## 8. applications テーブル

`applications` は求職者から求人への応募を表します。

主なカラム:

| カラム | 型 | 説明 |
| --- | --- | --- |
| `user_id` | bigint | 応募した求職者 |
| `job_id` | bigint | 応募先求人 |
| `status` | integer | 応募ステータス enum |
| `message` | text | 応募メッセージ |

### Application の enum

```ruby
enum :status, {
  pending: 0,
  reviewing: 1,
  accepted: 2,
  rejected: 3,
  withdrawn: 4
}
```

ステータスの意味:

| status | 意味 |
| --- | --- |
| `pending` | 応募直後 |
| `reviewing` | 企業が選考中 |
| `accepted` | 採用または通過 |
| `rejected` | 不採用 |
| `withdrawn` | 求職者が応募取り下げ |

### 重複応募防止

同じ求職者が同じ求人に対して、有効な応募を複数作れないようにしています。

```ruby
scope :active, -> { where.not(status: :withdrawn) }
```

`withdrawn` は取り下げ済みなので、有効応募には含めません。これにより、一度応募を取り下げた後は再応募できる設計です。

重複チェックの考え方:

```ruby
if Application.active.where(user_id: user_id, job_id: job_id).where.not(id: id).exists?
  errors.add(:base, "すでにこの求人に応募しています")
end
```

## 9. areas と license_types

### Area

`areas` は勤務地エリアを表します。

主なカラム:

- `name`
- `pref_code`

求人は `area_id` を持つため、エリア別に検索できます。

将来的なモデル設計:

```ruby
class Area < ApplicationRecord
  has_many :jobs
  validates :name, presence: true
end
```

### LicenseType

`license_types` は運送業務に必要な免許種別を表します。

主なカラム:

- `name`
- `code`

求人とは `jobs_license_types` 中間テーブルを通して多対多で結びます。

将来的なモデル設計:

```ruby
class LicenseType < ApplicationRecord
  has_and_belongs_to_many :jobs
  validates :name, presence: true
end
```

## 10. テスト設計

RSpec では主にモデルの関連、バリデーション、enum、scope を確認する想定です。

確認対象:

- Devise が `User`, `Company`, `Admin` で独立していること
- `Company has_many jobs`
- `User has_many applications`
- `User has_many applied_jobs through applications`
- `Job belongs_to company`
- `Job belongs_to area`
- `Job has_many applications`
- `Job has_and_belongs_to_many license_types`
- `Application belongs_to user`
- `Application belongs_to job`
- `Application` の重複応募防止
- `Job.published`
- `Job.active`
- `Job.expired`
- `Job.search`

FactoryBot では `user`, `company`, `admin`, `area`, `license_type`, `job`, `application` のテストデータを作成する想定です。

## 11. 今後実装予定の画面・機能

### 求職者向け

- 求人一覧
- 求人検索フォーム
- 求人詳細
- 応募フォーム
- 応募履歴
- 応募取り下げ

### 企業向け

- 企業ログイン
- 自社求人一覧
- 求人作成
- 求人編集
- 求人削除
- 応募者一覧
- 応募詳細
- 応募ステータス更新

### 管理者向け

- 管理者ログイン
- ダッシュボード
- 求人管理
- 企業管理
- 求職者管理
- 応募管理
- Sidekiq 管理画面のアクセス制御

### API・非同期処理

将来的には以下も実装予定です。

- `Api::V1::JobsController`
- `Api::V1::ApplicationsController`
- `ExpireJobsWorker`
- `SyncExternalJobsWorker`
- `ApplicationNotificationWorker`
- `StatusChangeNotificationWorker`

`ExpireJobsWorker` では、期限切れ求人を自動的に `closed` に変更する想定です。

## 12. 現在の実装上の注意点

現時点では、設計書や spec で期待されている状態に対して、一部モデル実装が未完成または修正途中です。

特に `src/app/models/job.rb` には以下のような修正が必要です。

- `where(published: true)` ではなく `where(status: statuses[:published])` を使う
- `expirex` は `expired` に直す
- `area_id employment_type` のような不要な行を削除する
- `search` scope の最後で `jobs` を返す
- `belongs_to :company :area` を `belongs_to :company` と `belongs_to :area` に分ける
- `dependent :destroy` を `dependent: :destroy` に直す
- enum のカンマ抜けを修正する
- `status` enum と `published` scope の衝突を避ける

お手本コードは `src/app/models/job.rb.md` にあります。

## 13. ChatGPT に渡すための説明プロンプト

以下を ChatGPT に貼ると、このプロジェクトの前提を伝えやすいです。

```text
私は Ruby on Rails 7.1 / MySQL / Devise / RSpec で、運送業界向け求人マッチングシステムを作っています。

Rails アプリ本体はリポジトリ直下ではなく src/ 配下にあります。

このシステムには User, Company, Admin の 3 種類の認証モデルがあります。Devise で devise_for :users, :companies, :admins を分けており、求職者、企業、管理者を別テーブル・別ログインスコープで管理します。

主なモデルは User, Company, Admin, Job, Application, Area, LicenseType です。

User は求職者で、Application を通して Job に応募します。Company は求人掲載企業で、複数の Job を持ちます。Admin は管理者です。Job は Company と Area に belongs_to し、Application を複数持ち、LicenseType とは jobs_license_types 中間テーブルで多対多です。Application は User と Job に belongs_to し、応募ステータスを enum で管理します。

Job には employment_type, salary_type, status の enum があります。status は draft, published, closed です。Job.search(params) で area_id, employment_type, salary_min, license_type_ids, keyword による検索を行う想定です。license_type_ids 検索では joins(:license_types) と distinct を使って重複求人を防ぎます。

Application には pending, reviewing, accepted, rejected, withdrawn の enum があります。同じ user_id と job_id の組み合わせで withdrawn 以外の有効応募がすでに存在する場合、重複応募を防止します。ただし withdrawn の応募しかない場合は再応募可能です。

現在は DB・モデル・認証まわりを実装している段階で、今後、求職者向け求人検索・応募画面、企業向け求人管理画面、管理者画面、API、Sidekiq による期限切れ求人処理や通知処理を実装する予定です。

この前提で、Rails のモデル設計、scope、validation、controller、view、RSpec について相談に乗ってください。
```

## 14. 実装方針の要約

このシステムの中核は、求人 `Job` と応募 `Application` です。認証モデルを 3 つに分けることで、求職者、企業、管理者の責務を明確に分離しています。求人検索は `Job.search` に集約し、画面側や controller 側に複雑な絞り込み条件を散らばらせない設計です。応募まわりでは、取り下げ済み応募を除いた有効応募だけを重複チェック対象にすることで、重複応募防止と再応募可能性の両方を実現しています。

今後は Pundit による権限制御、Sidekiq による非同期処理、RSpec によるモデル・controller・policy・worker テストを追加して、実務的な求人マッチングシステムとして完成度を高めていく方針です。
