#!/bin/bash

# ファイルバックアップスクリプト（storage/app/public内のファイル）
# 使用方法: ./scripts/backup_files.sh

set -e

# 設定
BACKUP_DIR="${BACKUP_DIR:-storage/backups}"
STORAGE_DIR="storage/app/public"
RETENTION_DAYS="${RETENTION_DAYS:-30}"

# バックアップディレクトリ作成
mkdir -p "$BACKUP_DIR"

# 日付を取得
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/files_backup_${DATE}.tar.gz"

echo "===== ファイルバックアップ開始 ====="
echo "バックアップ元: $STORAGE_DIR"
echo "バックアップファイル: $BACKUP_FILE"

# ファイルバックアップ
if [ -d "$STORAGE_DIR" ] && [ "$(ls -A $STORAGE_DIR)" ]; then
    tar -czf "$BACKUP_FILE" -C "$STORAGE_DIR" .
    
    if [ $? -eq 0 ]; then
        echo "✅ バックアップ成功: $BACKUP_FILE"
        
        # ファイルサイズを表示
        FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
        echo "ファイルサイズ: $FILE_SIZE"
    else
        echo "❌ バックアップ失敗"
        exit 1
    fi
else
    echo "⚠️ バックアップ対象のディレクトリが空または存在しません"
fi

# 古いバックアップファイルを削除（30日以上前）
echo "古いバックアップファイルを削除中..."
find "$BACKUP_DIR" -name "files_backup_*.tar.gz" -type f -mtime +$RETENTION_DAYS -delete

echo "===== バックアップ完了 ====="



















