#!/bin/bash

cd /usr/src/roundcube-behat-checker

sed 's/username/'$USERNAME'/;s/password/'$PASSWORD'/' variables.sh.example > variables.sh
sed -i 's#https.*roundcube/#'$URL'#' features/roundcube.feature

vendor/bin/behat
