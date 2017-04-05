# Roundcube checker

## Install

```bash
# git checkout somewhere
$ composer install
$ cp variables.sh.example variables.sh
```

* Edit the login information in the variables.sh file
* Edit the url and email settings in features/roundcube.feature (TODO, add these to variables.sh)

## Usage

Use the `check_behat.sh` script to tun the tests.

For more output you can call behat directly.

```
$ vendor/bin/behat
```

## Nagios config

```
define command{
    command_name  check_behat_roundcube
    command_line  /home/nagios/src/roundcube-checker/check_behat.sh
}

define service{
    use                 generic-service

    normal_check_interval       60
    retry_check_interval        10
    notification_interval       120

    host_name           mail.example.com

    service_description     Behat Roundcube
    check_command           check_behat_roundcube
}

```

## About

This project was developed by [Initfour websolutions](https://www.initfour.nl/) and [BrixIT](https://brixit.nl/).
