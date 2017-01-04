#!/bin/bash

# Get into the correct dir
cd `dirname $0`

# Run tests, discard the output.
./vendor/bin/behat > /dev/null

# Capture the return value.
RETURN=$?

if [ $RETURN == 0 ]
then
    echo "OK - Behat test successful"
    exit 0
#elif [ ??? ]
#then
#    echo "WARNING- $output"
#    exit 1
elif [ $RETURN == 1]
then
    echo "CRITICAL - Behat gave error"
    exit 2
else
       echo "UNKNOWN- ??? bebat ???"
       exit 3
fi
