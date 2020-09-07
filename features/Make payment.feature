@payment
Feature: Make payment
	Buyers can make payment after they checkout or from order details.

	Scenario: Making payment
		Given I am logged in
		And I just 'checkout' my order or I click on the 'make payment now' button on the 'order details' page
		When I input my credit/debit card number
		* name on card
		* expiration date
		* CVV
		Then I should be able to click on the 'make payment now' button to complete payment
    And see the 'order details' for the order
