# Roundcube checker

## Install / Execute

```bash
# git checkout somewhere
$ composer install
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
