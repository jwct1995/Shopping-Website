@cart
Feature: Checkout cart
	Buyers can checkout all items in cart before proceeding to payment

	Scenario: Checking out
		Given I am logged in
		And I am on the 'cart' page
		And there is at least 1 item in my cart
		When I click on the 'proceed to checkout' button
		Then I should enter my 'shipping address' and 'phone number'
		And be able to click 'checkout now' to make the order
		But I should not be able to checkout if the quantity is more than the remainding stock count
