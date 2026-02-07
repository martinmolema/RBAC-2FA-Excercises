#!/bin/bash
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
LOGFILE="/app/logs/script_run_${TIMESTAMP}.log"

echo "Running User Provisioning"  > $LOGFILE 2>&1
/usr/local/bin/python /app/sync.py >> $LOGFILE 2>&1