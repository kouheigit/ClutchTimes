#!/bin/bash

# 完全バックアップスクリプト（データベース + ファイル）
# 使用方法: ./scripts/backup_all.sh

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BASE_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$BASE_DIR"

echo "===== 完全バックアップ開始 ====="
echo "実行日時: $(date)"

# データベースバックアップ
if [ -f "$SCRIPT_DIR/backup.sh" ]; then
    bash "$SCRIPT_DIR/backup.sh"
else
    echo "⚠️ データベースバックアップスクリプトが見つかりません"
fi

# ファイルバックアップ
if [ -f "$SCRIPT_DIR/backup_files.sh" ]; then
    bash "$SCRIPT_DIR/backup_files.sh"
else
    echo "⚠️ ファイルバックアップスクリプトが見つかりません"
fi

echo "===== 完全バックアップ完了 ====="



















