#!/bin/sh
set -eu
file=/etc/nginx/conf.d/tsrepair-static-compression.conf
if test -e "$file"; then echo 'Configuration already exists; inspect before replacing.'; exit 1; fi
printf '%s\n' 'gzip_vary on;' 'gzip_comp_level 5;' 'gzip_min_length 1024;' 'gzip_types text/css application/javascript application/json application/xml image/svg+xml text/plain;' > "$file"
if nginx -t; then systemctl reload nginx; else rm "$file"; exit 1; fi
