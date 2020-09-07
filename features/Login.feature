@accounts
Feature: Login
  
  Scenario: Logging into your account
    Given I am not logged in
    When I click on the 'login' button
    Then I should be able to enter my id and password to login
    But I should be rejected if my id and password combination is incorrect