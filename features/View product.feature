@items
Feature: View product
	Buyer and sellers can view more details about a product when they click on it from the search page.

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
		