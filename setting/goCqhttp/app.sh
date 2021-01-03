#!/bin/bash

set -e

nohup "/go/cqhttp/go-cqhttp"

exec "$@"