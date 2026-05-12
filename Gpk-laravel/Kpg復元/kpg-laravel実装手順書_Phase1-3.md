# Phase 1-3: 環境構築からモデル定義まで

---

## Phase 0: プロジェクト準備（3-5日）

### 目標
Docker環境とLaravelプロジェクトの初期設定を完了する

### Step 0-1: Docker環境構築

#### 1. ディレクトリ構成作成
```bash
mkdir -p kpg-laravel/infra/docker/nginx
mkdir -p kpg-laravel/infra/docker/php
mkdir -p kpg-laravel/infra/docker/mysql
mkdir -p kpg-laravel/src
```

#### 2. docker-compose.yml 作成
```yaml
version: "3.9"
volumes:
  db-store:
  psysh-store:
services:
  app:
    build:
      context: .
      dockerfile: ./infra/docker/php/Dockerfile
      target: ${APP_BUILD_TARGET:-development}
    volumes:
      - type: bind
        source: ./src
        target: /data
      - type: volume
        source: psysh-store
        target: /root/.config/psysh
        volume:
          nocopy: true
    environment:
      - APP_DEBUG=${APP_DEBUG:-true}
      - APP_KEY=${APP_KEY:-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX}
      - APP_ENV=${APP_ENV:-local}
      - APP_URL=${APP_URL:-http://localhost}
      - DB_CONNECTION=${DB_CONNECTION:-mysql}
      - DB_HOST=${DB_HOST:-db}
      - DB_PORT=${DB_PORT:-3306}
      - DB_DATABASE=${DB_DATABASE:-laravel}
      - DB_USERNAME=${DB_USERNAME:-phper}
      - DB_PASSWORD=${DB_PASSWORD:-secret}

  web:
    build:
      context: .
      dockerfile: ./infra/docker/nginx/Dockerfile
    ports:
      - "8081:80"
    volumes:
      - type: bind
        source: ./src
        target: /data

  db:
    build:
      context: .
      dockerfile: ./infra/docker/mysql/Dockerfile
    ports:
      - "3306:3306"
    volumes:
      - type: volume
        source: db-store
        target: /var/lib/mysql
    environment:
      - MYSQL_DATABASE=${DB_DATABASE:-laravel}
      - MYSQL_USER=${DB_USERNAME:-phper}
      - MYSQL_PASSWORD=${DB_PASSWORD:-secret}
      - MYSQL_ROOT_PASSWORD=${DB_PASSWORD:-secret}

  phpmyadmin:
    image: phpmyadmin:5.2
    environment:
      - PMA_HOST=db
      - PMA_USER=phper
      - PMA_PASSWORD=secret
    links:
      - db
    ports:
      - "8080:80"
    volumes:
      - /sessions

  mailhog:
    image: mailhog/mailhog
    ports:
      - "8025:8025"
```

#### 3. PHP Dockerfile
```dockerfile
# infra/docker/php/Dockerfile
FROM php:8.1-fpm-bullseye AS base

WORKDIR /data

ENV TZ=UTC \
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
  && apt-get -y install --no-install-recommends \
    locales \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    bcmath \
  && composer config -g process-timeout 3600 \
  && composer config -g repos.packagist composer https://packagist.org

FROM base AS development
COPY ./infra/docker/php/php.development.ini /usr/local/etc/php/php.ini

FROM base AS deploy
COPY ./infra/docker/php/php.deploy.ini /usr/local/etc/php/php.ini
COPY ./src /data
RUN composer install -q -n --no-ansi --no-dev --no-scripts --no-progress --prefer-dist \
  && chmod -R 777 storage bootstrap/cache \
  && php artisan optimize:clear \
  && php artisan optimize
```

#### 4. Nginx Dockerfile
```dockerfile
# infra/docker/nginx/Dockerfile
FROM nginx:1.20

WORKDIR /data

ENV TZ=UTC

COPY ./infra/docker/nginx/*.conf /etc/nginx/conf.d/
```

#### 5. Nginx設定ファイル
```nginx
# infra/docker/nginx/default.conf
access_log /dev/stdout main;
error_log /dev/stderr warn;

server {
    listen 80;
    listen [::]:80;
    root /data/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 6. MySQL Dockerfile
```dockerfile
# infra/docker/mysql/Dockerfile
FROM mysql/mysql-server:8.0

ENV TZ=UTC

COPY ./infra/docker/mysql/my.cnf /etc/my.cnf
```

#### 7. MySQL設定ファイル
```ini
# infra/docker/mysql/my.cnf
[mysqld]
skip-host-cache
skip-name-resolve
datadir = /var/lib/mysql
socket = /var/lib/mysql/mysql.sock
secure-file-priv = /var/lib/mysql-files
user = mysql
pid-file = /var/run/mysqld/mysqld.pid

character_set_server = utf8mb4
collation_server = utf8mb4_0900_ai_ci

default-time-zone = SYSTEM
log_timestamps = SYSTEM

log-error = mysql-error.log

slow_query_log = 1
slow_query_log_file = mysql-slow.log
long_query_time = 1.0
log_queries_not_using_indexes = 0

general_log = 1
general_log_file = mysql-general.log

[mysql]
default-character-set = utf8mb4

[client]
default-character-set = utf8mb4
```

### Step 0-2: Laravelインストール

```bash
# 1. Dockerコンテナ起動
docker-compose up -d

# 2. appコンテナに入る
docker-compose exec app bash

# 3. Laravel 8.x インストール
composer create-project laravel/laravel . "^8.0"

# 4. 権限設定
chmod -R 777 storage bootstrap/cache

# 5. アプリケーションキー生成（既に実行済みの場合はスキップ）
php artisan key:generate

# 6. 動作確認
curl http://localhost:8081
```

### Step 0-3: 必要パッケージのインストール

```bash
# 認証システム（Laravel Breeze）
composer require laravel/breeze
php artisan breeze:install
npm install && npm run dev

# 管理画面（Laravel Admin）
composer require encore/laravel-admin
php artisan admin:install

# 決済API（Veritrans）
composer require veritrans/tgmdk

# その他必須パッケージ
composer require doctrine/dbal  # スキーマ変更用
composer require guzzlehttp/guzzle  # HTTP通信
```

### チェックポイント
- [ ] http://localhost:8081 でLaravelウェルカムページ表示
- [ ] http://localhost:8081/login でログイン画面表示
- [ ] http://localhost:8081/admin でLaravel Admin表示
- [ ] phpMyAdmin（http://localhost:8080）でDB接続確認

---

## Phase 1: データベース設計（1週間）

### 目標
全40テーブルのマイグレーションファイルを作成し、実行する

### 実装順序の重要性
⚠️ 外部キー制約があるため、**依存関係の順序**でテーブルを作成する必要があります。

### Step 1-1: ユーザー関連テーブル（Day 1）

#### 1. usersテーブル拡張
```bash
php artisan make:migration alter_users_table --table=users
```

```php
<?php
// database/migrations/2022_07_10_161047_alter_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 会員ID
            $table->string('member_id')->unique()->nullable()->after('id');
            
            // 個人情報
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_kana')->nullable();
            $table->string('first_kana')->nullable();
            $table->string('zip1', 3)->nullable();
            $table->string('zip2', 4)->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('tel', 20)->nullable();
            
            // 会社情報
            $table->string('company_name')->nullable();
            $table->string('company_kana')->nullable();
            $table->string('company_zip1', 3)->nullable();
            $table->string('company_zip2', 4)->nullable();
            $table->string('company_address1')->nullable();
            $table->string('company_address2')->nullable();
            $table->string('company_tel', 20)->nullable();
            $table->string('company_fax', 20)->nullable();
            
            // 送付先情報
            $table->string('send_name')->nullable();
            $table->string('send_kana')->nullable();
            $table->string('send_zip1', 3)->nullable();
            $table->string('send_zip2', 4)->nullable();
            $table->string('send_address1')->nullable();
            $table->string('send_address2')->nullable();
            $table->string('send_tel', 20)->nullable();
            
            // システム情報
            $table->integer('type')->default(1)->comment('1:一般, 2:オーナー');
            $table->integer('agree')->default(0)->comment('利用規約同意');
            $table->integer('status')->default(1)->comment('1:有効, 0:無効');
            $table->foreignId('user_id')->nullable()->comment('親ユーザーID（オーナーの場合）');
            
            // 論理削除
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'member_id', 'last_name', 'first_name', 'last_kana', 'first_kana',
                'zip1', 'zip2', 'address1', 'address2', 'tel',
                'company_name', 'company_kana', 'company_zip1', 'company_zip2',
                'company_address1', 'company_address2', 'company_tel', 'company_fax',
                'send_name', 'send_kana', 'send_zip1', 'send_zip2',
                'send_address1', 'send_address2', 'send_tel',
                'type', 'agree', 'status', 'user_id', 'deleted_at'
            ]);
        });
    }
}
```

### Step 1-2: ホテル関連テーブル（Day 1）

#### 1. hotelsテーブル
```bash
php artisan make:migration create_hotels_table
```

```php
<?php
// database/migrations/2022_07_10_161025_create_hotels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ホテル名');
            $table->string('address')->nullable()->comment('住所');
            $table->text('description')->nullable()->comment('説明');
            $table->integer('status')->default(1)->comment('1:有効, 0:無効');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
}
```

#### 2. hotel_userテーブル（多対多中間テーブル）
```bash
php artisan make:migration create_hotel_user_table
```

```php
<?php
// database/migrations/2022_07_10_161648_create_hotel_user_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelUserTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // 複合ユニークキー
            $table->unique(['hotel_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_user');
    }
}
```

### Step 1-3: カレンダー・予約関連（Day 2-3）

#### 1. calendarsテーブル
```bash
php artisan make:migration create_calendars_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('date')->comment('対象日');
            $table->date('start_date')->nullable()->comment('期間開始日');
            $table->date('end_date')->nullable()->comment('期間終了日');
            $table->integer('status')->default(1)->comment('1:予約可, 2:予約中, 3:予約済, 9:休業');
            $table->timestamps();
            
            // インデックス
            $table->index(['hotel_id', 'date']);
            $table->index(['user_id', 'start_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
```

#### 2. calendar_optionsテーブル
```bash
php artisan make:migration create_calendar_options_table
```

```php
<?php
class CreateCalendarOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('calendar_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body')->nullable();
            $table->integer('sort')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_options');
    }
}
```

#### 3. freedaysテーブル
```bash
php artisan make:migration create_freedays_table
```

```php
<?php
class CreateFreedaysTable extends Migration
{
    public function up()
    {
        Schema::create('freedays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('freedays')->default(0)->comment('残り泊数');
            $table->date('start_date')->comment('利用開始日');
            $table->date('end_date')->comment('有効期限');
            $table->integer('status')->default(1);
            $table->timestamps();
            
            $table->index(['user_id', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('freedays');
    }
}
```

#### 4. holidaysテーブル
```bash
php artisan make:migration create_holidays_table
```

```php
<?php
class CreateHolidaysTable extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->comment('休日');
            $table->string('name')->comment('休日名');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
}
```

#### 5. reservationsテーブル（重要）
```bash
php artisan make:migration create_reservations_table
```
```php
<?php
class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // 外部キー
            $table->foreignId('hotel_id')->constrained();
            $table->foreignId('user_id')->constrained()->comment('予約者');
            $table->foreignId('owner_id')->nullable()->constrained('users')->comment('オーナー');
            $table->foreignId('calendar_id')->nullable()->constrained();
            $table->foreignId('invitation_id')->nullable()->constrained();
            
            // 宿泊情報
            $table->date('checkin_date')->comment('チェックイン日');
            $table->date('checkout_date')->comment('チェックアウト日');
            $table->time('checkin_time')->nullable()->comment('チェックイン時刻');
            $table->time('checkout_time')->nullable()->comment('チェックアウト時刻');
            $table->integer('days')->default(1)->comment('宿泊日数');
            
            // ゲスト情報
            $table->string('name')->nullable()->comment('代表者名');
            $table->integer('adult')->default(0)->comment('大人人数');
            $table->integer('child')->default(0)->comment('子供人数');
            $table->integer('dog')->default(0)->comment('犬頭数');
            $table->text('note')->nullable()->comment('備考');
            
            // 施設情報
            $table->string('room_key', 50)->nullable()->comment('入室番号');
            $table->string('upload')->nullable()->comment('アップロードファイル');
            
            // 決済・ステータス
            $table->integer('payment')->default(0)->comment('0:現地払い, 1:クレジット');
            $table->integer('status')->default(1)->comment('ステータス');
            
            // 論理削除
            $table->softDeletes();
            $table->timestamps();
            
            // インデックス
            $table->index(['user_id', 'status']);
            $table->index(['checkin_date', 'status']);
            $table->index('owner_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
```

**ステータス定義**:
```
1: 申込中
2: 予約中
3: 予約確定
4: チェックイン済
5: チェックアウト済
8: キャンセル中
9: キャンセル
```

### Step 1-4: サービス・注文関連テーブル（Day 4-5）

#### 以下のテーブルを同様に作成
```bash
# サービス系
php artisan make:migration create_services_table
php artisan make:migration create_service_options_table

# 注文系
php artisan make:migration create_orders_table
php artisan make:migration create_order_details_table
php artisan make:migration create_add_orders_table
php artisan make:migration create_add_order_details_table

# カート系
php artisan make:migration create_carts_table
php artisan make:migration create_cart_details_table
php artisan make:migration create_tmp_order_details_table

# ログ系
php artisan make:migration create_veritrans_logs_table
```

テーブル詳細は実際のkpg-laravelプロジェクトの`database/migrations`を参照してください。

### Step 1-5: ポイント・その他テーブル（Day 6）

```bash
# ポイント系
php artisan make:migration create_user_points_table
php artisan make:migration create_user_point_logs_table

# お知らせ系
php artisan make:migration create_news_table
php artisan make:migration create_information_table
php artisan make:migration create_mail_templates_table

# その他
php artisan make:migration create_invitations_table
php artisan make:migration create_reservation_logs_table
php artisan make:migration create_release_logs_table
php artisan make:migration create_jobs_table
```

### Step 1-6: マイグレーション実行（Day 7）

```bash
# マイグレーション実行
php artisan migrate

# 実行されたマイグレーション確認
php artisan migrate:status

# 問題があれば巻き戻し
php artisan migrate:rollback

# 最初からやり直し
php artisan migrate:fresh
```

---

## Phase 2: モデル・定数定義（3-4日）

### Step 2-1: モデル作成（Day 1）

```bash
# 主要モデル作成（26個）
php artisan make:model Hotel
php artisan make:model Reservation
php artisan make:model Service
php artisan make:model ServiceOption
php artisan make:model Order
php artisan make:model OrderDetail
php artisan make:model Calendar
php artisan make:model CalendarOption
php artisan make:model Freeday
php artisan make:model Holiday
php artisan make:model Cart
php artisan make:model CartDetail
php artisan make:model TmpOrderDetail
php artisan make:model Invitation
php artisan make:model News
php artisan make:model Information
php artisan make:model UserPoint
php artisan make:model UserPointLog
php artisan make:model VeritransLog
php artisan make:model AddOrder
php artisan make:model AddOrderDetail
php artisan make:model MailTemplate
php artisan make:model ReservationLog
php artisan make:model ReleaseLog
php artisan make:model HotelUser
```

### Step 2-2: モデルの基本設定（Day 2）

#### User.php
```php
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;
    
    protected $fillable = [
        'member_id', 'name', 'email', 'password',
        'last_name', 'first_name', 'last_kana', 'first_kana',
        'zip1', 'zip2', 'address1', 'address2', 'tel',
        'company_name', 'company_kana', 'company_zip1', 'company_zip2',
        'company_address1', 'company_address2', 'company_tel', 'company_fax',
        'send_name', 'send_kana', 'send_zip1', 'send_zip2',
        'send_address1', 'send_address2', 'send_tel',
        'type', 'agree', 'status', 'user_id',
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

### Step 2-3: リレーション定義（Day 3）

各モデルにリレーションを追加していきます。

```php
// User.php にリレーション追加
public function hotels()
{
    return $this->belongsToMany(Hotel::class);
}

public function reservations()
{
    return $this->hasMany(Reservation::class);
}

public function orders()
{
    return $this->hasMany(Order::class);
}

public function userPoints()
{
    return $this->hasMany(UserPoint::class);
}
```

### Step 2-4: 定数クラス作成（Day 4）

```bash
mkdir app/Consts
```

完全な定数クラスは実際のkpg-laravelプロジェクトの`app/Consts`を参照してください。

### チェックポイント
- [ ] 全40テーブルが作成される
- [ ] php artisan migrate 成功
- [ ] 全26モデルが作成される
- [ ] リレーションが正しく動作
- [ ] tinkerで動作確認可能

---

## Phase 3: サービスクラス作成（完了）

FreedayServiceとPointServiceは前述のPhaseで説明済みです。

---

以上でPhase 1-3が完了です。次のPhase 4-6のファイルを参照してください。

