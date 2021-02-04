#!/usr/bin/env bash


set -e

echo "" > /etc/resolv.conf
echo "nameserver 114.114.114" >> /etc/resolv.conf
echo "nameserver 8.8.8.8" >> /etc/resolv.conf

if [[ ! -z "$@" ]]; then
    # The container is started to run some one-off command only.
    BOOT_MODE=TASK
else
    # The container is to launch some long running services (e.g., web server, job worker, etc).
    BOOT_MODE=SERVICE
fi
export BOOT_MODE

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf -n

exec "$@"