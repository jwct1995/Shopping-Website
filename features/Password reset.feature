@accounts
Feature: Password reset
  Users are able to reset their password through email verification if they forgot their passwords

  Scenario: Reseting password if forgotten
    Given I am not already logged in
    And I am on the 'login' page
    When I click on the 'forgot password' button
    Then I should be able to enter my email address
    And have a password reset link sent to my email
    And click on the link to enter my new password to change it
    