#!/bin/sh

# Check if it is a master user login
if [ "$MASTER_USER" != "" ]; then
    /usr/local/bin/master-user-report.pl --remote-ip=$IP --server-ip=$LOCAL_IP --master=$MASTER_USER --account="$USER"
fi

# Assign the master user to allow him to read all mailboxes
export MASTER_USER="$USER"

exec "$@"

