#!/bin/bash

set -e

/etc/init.d/cron start
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl start all
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
exec "$@"