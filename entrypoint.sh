#!/bin/bash
set -e

# MPM競合を解消
for f in /etc/apache2/mods-enabled/mpm_*.conf /etc/apache2/mods-enabled/mpm_*.load; do
    [ -e "$f" ] && rm -f "$f"
done

# mpm_preforkを有効化
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

exec apache2-foreground
