# Roundcube checker

Fakes a browser session to login to Roundcube webmail, sent a test mail, and check settings.

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

Here's an example Nagios configuration.
To use the passive check you need some script that either receives the mail or reads mailserver logs, and sends a passive result to Nagios.

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

# Passibe check to receive the mail from the Behat Roundcube test above
define service{
    use             passive-generic     ; Name of service template to use
    name                roundcube-behat

    active_checks_enabled       0   ; Active service checks are disabled
    passive_checks_enabled      1   ; Passive service checks are enabled

    check_freshness         1
    freshness_threshold     7200                   ; 2 hour threshold, since backups may not always finish at the same time
    check_command           return-misc!2!"No info in 2h"         ; this command is run only if the service results are "stale"

    host_name               mail.example.com
    service_description     roundcube mail via behat
}


```

## About

This project was developed by [Initfour websolutions](https://www.initfour.nl/) and [BrixIT](https://brixit.nl/).
