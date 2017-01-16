Feature: Roundcube should work

  Background:
    Given there is an roundcube server at "https://www.initfour.nl/roundcube/"
    And there is an account defined by the environment

  Scenario: Logging in
    When I log in
    Then I should get a 2xx http response
    And I should have the element "#mailview-bottom"

  Scenario: Sending an email
    When I log in
    And I go to task "mail" action "compose"
    Then I should have the element "#composeoptions"
    When I submit the email page with the information:
      | field     | value                  |
      | recipient | martijn@brixit.nl      |
      | subject   | Test email vanaf behat |
      | body      | HALLOOO                |

  Scenario: Check if the password change plugin exists
    When I log in
    And I go to task "settings" action "plugin.password"
    Then I should have the element "#newpasswd"

  Scenario: Check if spamassassin plugin exists
    When I log in
    And I go to task "settings" action "plugin.sauserprefs"
    Then I should have the element "#rcmrowbayes"