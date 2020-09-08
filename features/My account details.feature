@accounts
Feature: My account details
  Let users view their email and password.

  Scenario: Viewing account details
    Given I am logged in
    When I click on 'my profile' Icon
    Then I should be able to see my email address
    But I should not be able to change them
    