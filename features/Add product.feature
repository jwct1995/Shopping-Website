@store
Feature: Add product
  Sellers can add a new product to their store for sale

  Scenario: Adding a new product
    Given I am logged in
    And I am on the 'seller centre' page
    When I click on the 'add a new product' button
    And I input the product name into the 'product name: ' field
    * the price into the 'price: ' field
    * stock into the 'stock: ' field
    And I click on the 'save and publish' button
    Then the new product should be listed on my store
    But I should be prompted if the product name is already in my store
    And the price or stock is negative
    