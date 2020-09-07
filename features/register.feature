@accounts
Feature: Register
  Buyer, seller and admin can register for an account.

  Scenario: Registering for an account
    Given I am not logged in
    When I click on the 'register' button
    Then I should be able to enter my email address and password to register for an account
    But I should be prompted if the email is already registered
    