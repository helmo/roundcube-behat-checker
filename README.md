# Roundcube checker

Fakes a browser session to login to Roundcube webmail, sent a test mail, and check settings.

## Install

```bash
# git checkout somewhere
$ composer install
$ cp variables.sh.example variables.sh
```

* Edit the login information in the variables.sh file
* Ensure that the webmail user gets the Interface skin 'Larry'.
* The last two steps in features/roundcube.feature depend on roundcube plugings, disable or extend to match your setup.

## Usage

Use the `check_behat.sh` script to run the tests.

For more output you can call behat directly.

```
$ vendor/bin/behat
```

Alternatively you cal also run it in docker.

To build teh container:
```
$ make
```

Then to run:
```
$ make run
```


## Icinga config

Here's an example Nagios configuration.
To use the passive check you need some script that either receives the mail or reads mailserver logs, and sends a passive result to Icinga.

```
object CheckCommand "check_behat_roundcube" {
        command = [ "/var/lib/nagios/src/roundcube-behat-checker/check_behat.sh" ]
        arguments = {   }
}
```

```
apply Service "behat-roundcube-active"  {
  import "generic-service"

  display_name = "Behat Roundcube active"

  check_interval = 1h
  retry_interval = 10m

  check_command = "check_behat_roundcube"

  assign where host.name == "mail.example.com"
}
apply Service "behat-roundcube-passive"  {
  import "generic-passive-service"

  check_interval = 7d
  vars.notification_interval = 1d
  display_name = "Behat Roundcube - passive result"
  vars.dummy_text = generate_passive_dummy_text("$host.name$", "$service.name$")

  assign where host.name == "mail.example.com"
}

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
