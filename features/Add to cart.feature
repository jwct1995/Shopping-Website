@cart
Feature: Add to cart
  Buyers can add items to cart to checkout later
  
  Scenario: Adding items to cart
    Given I am on the product page
    And I am logged in
    When I adjust the quantity using the '^' or 'v' buttons
    And I click on the 'add to cart' button
    Then I should see the item in my cart later
    And the item quantity should be what I added in
    But I cannot add in more than the total stock count for that item