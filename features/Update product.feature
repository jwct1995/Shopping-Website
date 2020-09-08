@store
Feature: Update product
  Sellers can change the details of existing products

  Scenario: Updating product details
    Given I am logged in
    And I am on the 'my products' page
    And I click on an existing product name
    Then I should be able to change the product name
    * price
    * stock
    But I should be prompted if the product name is already in my store
    And the price or stock is negative
    