@store @order
Feature: Sales order
  Sellers can view the order of a sale

  Scenario: Viewing sales order
    Given I am logged in
    And I am on the 'seller centre' page
    When I click on 'sales order'
    Then I should see a list of my sales order and their details
