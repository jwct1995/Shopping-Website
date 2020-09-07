@search @items
Feature: Search for products
  Buyer and sellers can search for products by search phrase.

    Given I am on the website
    When I click on the 'search bar' in the top section of the screen
    And I input a search phrase
    And I hit the 'search' icon or hit 'enter'
    Then I should see a list of similar products with their price
