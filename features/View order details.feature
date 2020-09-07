@orders
Feature: View order details
	Buyers should make payment after they checkout cart.

	Scenario: View order details from 'my orders' page
		Given I am logged in
		And I am on the 'my orders' page
		When I click on the 'order number' text
		Then I should see the details for that order
		* status
		* cost
    * items with quantity count
		* shipping address
		* seller

