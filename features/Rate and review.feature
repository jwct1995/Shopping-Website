@review
Feature: Rate and review
  Buyers can rate and review their product after they recieve it

  Scenario: Rating and reviewing a product
    Given I am logged in
    And I am on the 'product' page
    When I have made a purchase of that product
    Then I should be able to rate the product from 1-5 stars
    And give an optional review if I desire
    