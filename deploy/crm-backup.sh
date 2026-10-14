#!/usr/bin/env bash
# Nightly backup of the CRM database and private files (signatures, photos).
# Install: sudo cp deploy/crm-backup.sh /usr/local/bin/tsrepair-backup && sudo chmod 700 /usr/local/bin/tsrepair-backup
# Cron (root): 30 3 * * * /usr/local/bin/tsrepair-backup >> /var/log/tsrepair-backup.log 2>&1
set -euo pipefail

APP=/var/www/html/tsrepairservice
DEST=/var/backups/tsrepair
DB=tsrepairservice
KEEP_DAYS=30
STAMP=$(date +%Y-%m-%d_%H%M)

umask 077
mkdir -p "$DEST"

# Custom-format dump: compressed, restorable with pg_restore.
sudo -u postgres pg_dump --format=custom "$DB" > "$DEST/db_$STAMP.dump"

# Signatures and photos live on the private disk.
tar -czf "$DEST/private_$STAMP.tar.gz" -C "$APP/storage/app" private

find "$DEST" -type f -mtime +"$KEEP_DAYS" -delete
echo "$(date -Is) backup ok: $(du -sh "$DEST" | cut -f1) in $DEST"
