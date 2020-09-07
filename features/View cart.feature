@cart
Feature: View cart
  Buyers can go to the 'cart' page to view their items in cart

  Scenario: Go to cart
    Given I am logged in
    When I click on the 'cart' button
    Then I should see the list of items in my cart
    * each item quantity
    * their price
    * the total price
    But it should show 'Your cart is empty' if my cart is empty
    