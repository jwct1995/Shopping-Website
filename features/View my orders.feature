@items
Feature: Payment
   Buyers should make payment after they checkout cart.

    Given I am on the 'checkout' page
    And I have entered my 'shipping address' and 'phone number'
    When I click on the 'proceed to payment' button
    And I input my credit/debit card number
    * name on card
    * expiration date
    * CVV
    Then I should be able to click on the 'make payment now' button to complete payment
