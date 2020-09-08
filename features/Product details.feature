@store
Feature: Product details
	Sellers can add a new product to their store.

	Scenario: Viewing a product information
		Given I just searched for an item
		And I am on the 'search' page
		When I click on an 'product' name
		Then I should be on the 'product' page where I can see more details
		* Name
		* Price
		* Stock availability
		* Rating
		* Reviews