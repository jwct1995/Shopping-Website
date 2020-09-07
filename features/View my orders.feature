@orders
Feature: View my orders
	Buyers can view the past and present orders on the 'my orders' page

	Scenario: Viewing order history
		Given I am logged in
		When I click on the 'my orders' button
		Then I should see my past and present orders with their order number
		* status
		* cost
		* items with quantity count
