#!/bin/bash

set -e

/etc/init.d/cron start
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl start all
exec "$@"