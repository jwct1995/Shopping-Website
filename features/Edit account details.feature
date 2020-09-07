@accounts
Feature: Edit account details
  Edit login credentials (email and password)
  
  Scenario: Going to 'edit account details' page
    Given I am logged in
    And I am on 'my profile' page
    When I click on 'edit account details' button
    Then I should be on the 'edit account details' page

  Scenario: Updating email
    Given I am on the 'edit account details' page
    When I input my new email address into my email address box
    And I click on the 'update email address' button
    Then I should receive a confirmation email in my new email inbox
    And the change is confirmed when I click on the confirmation link
    And an email is sent to the old email address informing of the change
    But I should not be able to change my email to an existing registered one

  Scenario: Updating password
    Given I am on the 'edit account details' page
    When I click on 'change password' button
    Then I should be asked to input my 'current password' in a field
    * 'new password' in another field
    * 're-enter new password' in another field
    And click 'change password' to change my new password
    But my 'current password' should be correct and 'new password' must be same as 're-enter new password'
    