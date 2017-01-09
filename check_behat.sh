#!/bin/bash

# Get into the correct dir
cd `dirname $0`

source ./variables.sh

# Run tests, discard the output.
./vendor/bin/behat > /dev/null

# Capture the return value.
RETURN=$?

if [ $RETURN == 0 ]
then
    echo "OK - Behat test successful"
    exit 0
else
    echo "CRITICAL - Behat gave error"
    exit 2
fi
