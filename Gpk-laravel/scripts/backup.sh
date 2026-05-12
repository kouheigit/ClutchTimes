#!/bin/bash

# データベースバックアップスクリプト
# 使用方法: ./scripts/backup.sh

set -e

# 設定
BACKUP_DIR="${BACKUP_DIR:-storage/backups}"
DB_NAME="${DB_DATABASE:-laravel}"
DB_USER="${DB_USERNAME:-phper}"
DB_PASS="${DB_PASSWORD:-secret}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
RETENTION_DAYS="${RETENTION_DAYS:-30}"

# バックアップディレクトリ作成
mkdir -p "$BACKUP_DIR"

# 日付を取得
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/db_backup_${DATE}.sql.gz"

echo "===== データベースバックアップ開始 ====="
echo "データベース: $DB_NAME"
echo "バックアップファイル: $BACKUP_FILE"

# データベースバックアップ
mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" \
    --single-transaction \
    --routines \
    --triggers \
    "$DB_NAME" | gzip > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "✅ バックアップ成功: $BACKUP_FILE"
    
    # ファイルサイズを表示
    FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    echo "ファイルサイズ: $FILE_SIZE"
else
    echo "❌ バックアップ失敗"
    exit 1
fi

# 古いバックアップファイルを削除（30日以上前）
echo "古いバックアップファイルを削除中..."
find "$BACKUP_DIR" -name "db_backup_*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete

echo "===== バックアップ完了 ====="



















