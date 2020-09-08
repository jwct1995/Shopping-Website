@store
Feature: My products
  Sellers can view a list of product they have added

  Scenario: Going to my products page
    Given I am logged in
    And I am on the 'seller centre' page
    When I click on the 'my products' button
    Then I should see a list of products I have added