#/bin/bash
echo 511 > /proc/sys/net/core/somaxconn
echo 1 > /proc/sys/vm/overcommit_memory