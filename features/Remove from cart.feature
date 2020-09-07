@cart
Feature: Remove from cart
	Buyers can remove items from cart that they no longer want to buy

	Scenario: Removing item from cart
		Given I am logged in
		And I am on the 'cart' page
		When I click on the 'remove from cart' button beside a product
		Then I should be prompted with an 'Are you sure you want to remove item from cart?' message
		And I should be able to press the 'yes' button
		And the item will be removed from my cart
		But the item should not be removed if I hit the 'no' button