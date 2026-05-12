# 1日目 DB・モデル・認証まわり 実装指示書

## ゴール

運送業界向け求人マッチングシステムの土台として、DB、モデル、Devise認証、初期データを実務で扱える最低限の状態にする。

1日目の完了条件は、以下を満たすこと。

- `User`, `Company`, `Admin` の3種類の認証が分離されている
- 求職者が求人に応募できるデータ構造になっている
- 企業が求人を管理できるデータ構造になっている
- 求人を地域、免許、雇用形態、給与、キーワードで検索できる土台がある
- seedで動作確認用の最低限データを投入できる
- `db:create`, `db:migrate`, `db:seed`, モデルテストが通る

## 作業前提

このプロジェクトではRailsアプリ本体は `src/` 配下にあるため、以下の指示に出てくるRails標準パスは `src/` から見たパスとして扱う。

例:

- `Gemfile` は `src/Gemfile`
- `config/routes.rb` は `src/config/routes.rb`
- `db/seeds.rb` は `src/db/seeds.rb`
- `app/models/user.rb` は `src/app/models/user.rb`

Docker環境を使う場合は、Railsコマンドは基本的に以下の形で実行する。

```bash
docker compose run --rm app bundle exec rails ...
```

## 1. Gemfile

### 対象

`src/Gemfile`

### 記述する内容

Railsアプリの土台に必要なgemが入っているか確認する。

必須で確認するgem:

- `rails`
- `mysql2`
- `devise`
- `pundit`
- `sidekiq`
- `rspec-rails`
- `factory_bot_rails`
- `faker`
- `shoulda-matchers`

### 指示

- DBはMySQLを使うため、`mysql2` を入れる
- 3種類ログインをDeviseで作るため、`devise` を入れる
- 権限管理の土台として `pundit` を入れる
- 後続日の非同期処理に備えて `sidekiq` を入れる
- モデル確認用にRSpec系gemを `development, test` または `test` に入れる
- gemを追加・変更したら `bundle install` を実行する

## 2. database.yml

### 対象

`src/config/database.yml`

### 記述する内容

MySQLに接続できる設定を書く。

必要な設定:

- adapter: `mysql2`
- encoding: `utf8mb4`
- database
  - development: `transport_jobs_development`
  - test: `transport_jobs_test`
  - production: `transport_jobs_production`
- username
- password
- host

### 指示

- Docker環境では `DATABASE_HOST`, `DATABASE_USER`, `DATABASE_PASSWORD` から取得できるようにする
- ローカル実行時のfallback値も用意する
- developmentとtestのDB名は必ず分ける

## 3. routes.rb

### 対象

`src/config/routes.rb`

### 記述する内容

Deviseの認証ルートを3種類分けて定義する。

必要なルート:

- `devise_for :users`
- `devise_for :companies`
- `devise_for :admins`

### 指示

- 求職者、企業、管理者を同じログインテーブルに混ぜない
- それぞれ別のURLでログインできるようにする
- ルート確認では以下が出ることを確認する
  - `/users/sign_in`
  - `/companies/sign_in`
  - `/admins/sign_in`

## 4. users migration

### 対象

`src/db/migrate/*_devise_create_users.rb`

### 記述する内容

求職者用の `users` テーブルを作る。

必要なカラム:

- `name`
- `email`
- `encrypted_password`
- `reset_password_token`
- `reset_password_sent_at`
- `remember_created_at`
- `created_at`
- `updated_at`

必要なindex:

- `email` unique
- `reset_password_token` unique

### 指示

- `email` はDeviseログインに使うため必須
- `name` は求職者表示名として使う
- CompanyやAdminとは別テーブルにする

## 5. companies migration

### 対象

`src/db/migrate/*_devise_create_companies.rb`

### 記述する内容

企業用の `companies` テーブルを作る。

必要なカラム:

- `name`
- `email`
- `encrypted_password`
- `reset_password_token`
- `reset_password_sent_at`
- `remember_created_at`
- `created_at`
- `updated_at`

必要なindex:

- `email` unique
- `reset_password_token` unique

### 指示

- `name` は会社名として使う
- 企業は求人を複数持つ前提にする
- UserやAdminとは別テーブルにする

## 6. admins migration

### 対象

`src/db/migrate/*_devise_create_admins.rb`

### 記述する内容

管理者用の `admins` テーブルを作る。

必要なカラム:

- `name`
- `email`
- `encrypted_password`
- `reset_password_token`
- `reset_password_sent_at`
- `remember_created_at`
- `created_at`
- `updated_at`

必要なindex:

- `email` unique
- `reset_password_token` unique

### 指示

- 管理者は管理画面やSidekiq画面のアクセス制御に使う
- UserやCompanyとは別テーブルにする

## 7. areas migration

### 対象

`src/db/migrate/*_create_areas.rb`

### 記述する内容

求人の勤務地エリアを表す `areas` テーブルを作る。

必要なカラム:

- `name`
- `pref_code`
- `created_at`
- `updated_at`

### 指示

- `name` は都道府県名やエリア名として使う
- `pref_code` は都道府県コードとして使う
- 求人検索で `area_id` による絞り込みができるようにする

## 8. license_types migration

### 対象

`src/db/migrate/*_create_license_types.rb`

### 記述する内容

運送求人に必要な免許種別を表す `license_types` テーブルを作る。

必要なカラム:

- `name`
- `code`
- `created_at`
- `updated_at`

### 指示

- `name` は画面表示用の免許名として使う
- `code` は内部識別子として使う
- 求人検索で免許種別による絞り込みができるようにする

## 9. jobs migration

### 対象

`src/db/migrate/*_create_jobs.rb`

### 記述する内容

求人情報を表す `jobs` テーブルを作る。

必要なカラム:

- `company_id`
- `area_id`
- `title`
- `description`
- `employment_type`
- `salary_type`
- `salary_min`
- `salary_max`
- `status`
- `published_at`
- `expires_at`
- `created_at`
- `updated_at`

必要な外部キー:

- `company_id` references `companies`
- `area_id` references `areas`

### 指示

- 求人は必ず1つの企業に所属する
- 求人は必ず1つの勤務地エリアを持つ
- `employment_type`, `salary_type`, `status` はモデル側でenumにする
- 公開求人、下書き求人、募集終了求人を区別できるようにする

## 10. applications migration

### 対象

`src/db/migrate/*_create_applications.rb`

### 記述する内容

求職者の応募を表す `applications` テーブルを作る。

必要なカラム:

- `user_id`
- `job_id`
- `status`
- `message`
- `created_at`
- `updated_at`

必要な外部キー:

- `user_id` references `users`
- `job_id` references `jobs`

### 指示

- 応募は必ず1人の求職者に所属する
- 応募は必ず1つの求人に所属する
- `status` はモデル側でenumにする
- 重複応募はモデル側で防止する

## 11. join table migration

### 対象

`src/db/migrate/*_create_join_tables.rb`
または
`src/db/migrate/*_create_join_table_jobs_license_types.rb`

### 記述する内容

求人と免許種別の多対多関係を表すjoin tableを作る。

必要なテーブル:

- `jobs_license_types`

必要なカラム:

- `job_id`
- `license_type_id`

必要なindex:

- `[:job_id, :license_type_id]` unique
- `[:license_type_id, :job_id]`

### 指示

- 1つの求人に複数の免許を紐付けられるようにする
- 1つの免許が複数の求人に紐付けられるようにする
- 同じ求人と免許の組み合わせが重複登録されないようにする

## 12. User model

### 対象

`src/app/models/user.rb`

### 記述する内容

求職者の認証と応募関連を定義する。

必要な内容:

- Devise設定
- `has_many :applications`
- `has_many :applied_jobs, through: :applications, source: :job`
- `applied_to?(job)` メソッド

### 指示

- `applications` はユーザー削除時に一緒に削除する
- `applied_to?(job)` では取り下げ済み以外の応募があるか確認する
- 応募ボタン表示や重複応募防止で使えるようにする

## 13. Company model

### 対象

`src/app/models/company.rb`

### 記述する内容

企業の認証と求人関連を定義する。

必要な内容:

- Devise設定
- `has_many :jobs`
- `name` のpresence validation

### 指示

- `jobs` は企業削除時に一緒に削除する
- 企業名なしで登録できないようにする
- 企業画面では `current_company.jobs` を基本にする

## 14. Admin model

### 対象

`src/app/models/admin.rb`

### 記述する内容

管理者の認証を定義する。

必要な内容:

- Devise設定

### 指示

- 管理画面アクセス制御で `authenticate_admin!` を使えるようにする
- UserやCompanyの認証とは混ぜない

## 15. Job model

### 対象

`src/app/models/job.rb`

### 記述する内容

求人の関連、enum、validation、検索scopeを定義する。

必要な関連:

- `belongs_to :company`
- `belongs_to :area`
- `has_many :applications`
- `has_and_belongs_to_many :license_types`

必要なenum:

- `employment_type`
  - `full_time`
  - `part_time`
  - `contract`
  - `temporary`
- `salary_type`
  - `monthly`
  - `hourly`
  - `annual`
- `status`
  - `draft`
  - `published`
  - `closed`

必要なvalidation:

- `title`
- `description`
- `employment_type`
- `salary_type`
- `status`

必要なscope:

- `published`
- `active`
- `expired`
- `search`

### 指示

- `active` は公開中かつ期限切れでない求人を返す
- `expired` は公開中かつ期限切れの求人を返す
- `search` では以下の条件で絞り込めるようにする
  - `area_id`
  - `employment_type`
  - `salary_min`
  - `license_type_ids`
  - `keyword`
- `license_type_ids` 検索ではjoinして重複求人を返さないようにする

## 16. Application model

### 対象

`src/app/models/application.rb`

### 記述する内容

応募の関連、enum、validation、重複応募防止、取り下げ可否を定義する。

必要な関連:

- `belongs_to :user`
- `belongs_to :job`

必要なenum:

- `pending`
- `reviewing`
- `accepted`
- `rejected`
- `withdrawn`

必要なvalidation:

- `status`
- 同じユーザーが同じ求人に有効な応募を重複作成できないこと

必要なscope:

- `active`

必要なメソッド:

- `withdrawable?`

### 指示

- `active` は `withdrawn` 以外を返す
- 重複応募チェックでは、取り下げ済み応募は重複扱いにしない
- `withdrawable?` は `pending` と `reviewing` のときだけtrueにする

## 17. Area model

### 対象

`src/app/models/area.rb`

### 記述する内容

勤務地エリアと求人の関連を定義する。

必要な内容:

- `has_many :jobs`
- `name` のpresence validation

### 指示

- エリア名なしの登録はできないようにする
- 求人検索フォームの選択肢として使えるようにする

## 18. LicenseType model

### 対象

`src/app/models/license_type.rb`

### 記述する内容

免許種別と求人の関連を定義する。

必要な内容:

- `has_and_belongs_to_many :jobs`
- `name` のpresence validation

### 指示

- 免許名なしの登録はできないようにする
- 求人検索フォームの選択肢として使えるようにする
- 求人作成フォームで複数選択できる前提にする

## 19. seeds.rb

### 対象

`src/db/seeds.rb`

### 記述する内容

開発環境で最低限の動作確認ができる初期データを作る。

必要なデータ:

- 求職者サンプル
- 企業サンプル
- 管理者サンプル
- エリア複数件
- 免許種別複数件
- 公開求人複数件

### 指示

- `find_or_create_by!` または `find_or_initialize_by` を使い、何度実行しても重複しないseedにする
- ログイン確認用に分かりやすいメールアドレスとパスワードを設定する
- 求人には企業、エリア、免許種別を必ず紐付ける
- 公開求人は `status: :published` にする
- 検索確認ができるよう、エリアや免許種別が異なる求人を複数作る

## 20. モデルspec

### 対象

`src/spec/models/*_spec.rb`

### 記述する内容

1日目の土台が壊れていないことを確認するモデルテストを書く。

確認する内容:

- Deviseの3種類認証スコープ
- Userの応募関連
- Companyの求人関連
- Jobの関連、enum、validation、scope
- Applicationの関連、enum、validation、重複応募防止
- Areaの求人関連
- LicenseTypeの求人関連

### 指示

- `pending` のまま放置しない
- FactoryBotで関連モデルを作れるようにfactoryも整える
- `rspec` が成功する状態にする

## 21. 実行コマンド

Docker環境で確認する場合:

```bash
docker compose run --rm app bundle install
docker compose run --rm app bundle exec rails db:create db:migrate db:seed
docker compose run --rm app bundle exec rails routes -g sign_in
docker compose run --rm app bundle exec rspec
```

ローカル環境で確認する場合:

```bash
cd src
bundle install
bundle exec rails db:create db:migrate db:seed
bundle exec rails routes -g sign_in
bundle exec rspec
```

## 22. 完了確認チェックリスト

- `bundle install` が成功する
- `rails db:create` が成功する
- `rails db:migrate` が成功する
- `rails db:seed` が成功する
- `/users/sign_in` が存在する
- `/companies/sign_in` が存在する
- `/admins/sign_in` が存在する
- `User` が `Application` を複数持てる
- `Company` が `Job` を複数持てる
- `Job` が `Company`, `Area`, `LicenseType` と紐付く
- `Application` が `User`, `Job` と紐付く
- 同じ求人への有効な重複応募ができない
- 取り下げ済み応募がある場合は再応募できる
- 公開中求人だけを取得するscopeがある
- 地域、雇用形態、給与、免許、キーワードで検索するscopeがある
- seedでサンプルログインユーザー、企業、管理者、求人、地域、免許が作成される
- RSpecが成功する

## 23. レビュー時に見るポイント

- 認証モデルを1つのテーブルにまとめていないか
- `users`, `companies`, `admins` のDeviseルートが分離されているか
- 外部キーが必要なテーブルに設定されているか
- 求人が企業なし、エリアなしで作れない設計になっているか
- 応募がユーザーなし、求人なしで作れない設計になっているか
- enumの値がDBのintegerと対応しているか
- seedが冪等になっているか
- テストが `pending` のまま残っていないか
