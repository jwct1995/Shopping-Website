@store
Feature: My products
  Sellers can add a new product to their store for sale

  Scenario: Adding a new product
    Given I am logged in
    And I am on the 'seller centre' page
    When I click on the 'my products' button
    Then I should see a list of products I have added