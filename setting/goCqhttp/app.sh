#!/bin/bash

set -e
cd /go/cqhttp
nohup ./go-cqhttp > /go/cq.log 2>&1 &
exec "$@"