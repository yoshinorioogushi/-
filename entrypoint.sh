#!/bin/bash
set -e

# RailwayのPORT環境変数に対応（デフォルト80）
PORT=${PORT:-80}

echo "Starting Apache on port $PORT"

# ports.confのポートを書き換え
sed -i "s/Listen 80$/Listen $PORT/" /etc/apache2/ports.conf

# VirtualHostのポートを書き換え
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf

# 確認
echo "ports.conf:"
cat /etc/apache2/ports.conf

exec apache2-foreground
