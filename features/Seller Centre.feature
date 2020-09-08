@store
Feature: Seller centre
  Seller can go to seller centre to manage their store

  Scenario: Going to seller centre
    Given I am logged in
    When I click on the 'seller centre' button
    Then I should be taken to the 'seller centre' page where I can manage my store
    