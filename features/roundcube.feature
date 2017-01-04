Feature: Roundcube should work

  Background:
    Given there is an roundcube server at "https://www.initfour.nl/roundcube/"
    And there is an account "brixit" with the password "GQxPGRv"

  Scenario: Logging in
    When I log in
    Then I should get a 2xx http response
    And I should have the element "#mailview-bottom"

  Scenario: Sending an email
    When I log in
    And I go to task "mail" action "compose"
    Then I should have the element "#composeoptions"