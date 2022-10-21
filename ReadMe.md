## Requirements

* PHP 8.0
* Composer

## Assumptions

* No persistence of data or sessions is necessary, array is used to encapsulate products and basket items
* Product code is unique.
* The first delivery charge rule that is applicable is used.
* Total discount offered every n-th item is variable.
* Totals are only calculated after all the items are added to the basket.

## Usage

index.php file contains all the executed test cases. It can be run on console as well as on the browser. Don't forget to run the `composer install` command first.

```php
use Acme\Basket;
use Acme\Catalogue;
use Acme\Product;

// Create a new catalogue
$catalogue = new Catalogue(); 

// Add product to catalogue
$catalogue->addProduct(new Product('R01', 'Red Widget', 32.95));

// Create a new basket
$basket = new Basket();

// Instantiate catalog for the basket
$basket->setCatalogue($catalogue);

// Add a special offer rule.
$basket->addSpecialOffer('R01', 2, 0.5);

// Add a delivery charge rule
$basket->addDeliveryChargeRule(0, 50, 4.95);

// Add a product to the basket by product code
$basket->add('R01');

// Get the total cost of basket after taking into account the delivery and offer rules
$grandTotal = $basket->getTotal();

// clear Basket
$basket->clearBasket();

```