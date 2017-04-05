# Roundcube checker

## Install / Execute

```bash
# git checkout somewhere
$ composer install
$ cp variables.sh.example variables.sh
$ vendor/bin/behat
```

Edit the login information in the variables.sh file
Edit the url and email settings in features/roundcube.feature (TODO, add to variables.sh)


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
