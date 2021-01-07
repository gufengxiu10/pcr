#!/bin/bash

set -e

# 程序后台运行
nohup python /www/PixivBiu/run.py >/www/pixivbiu.log 2>&1 &

exec "$@"
