<?php

require __DIR__ . '/vendor/autoload.php';

use Acme\Basket;
use Acme\Catalogue;
use Acme\Product;

$catalogue = new Catalogue();

$catalogue->addProduct(new Product('Red Widget', 'R01', 32.95));
$catalogue->addProduct(new Product('Green Widget', 'G01', 24.95));
$catalogue->addProduct(new Product('Blue Widget', 'B01', 7.95));

$basket = new Basket();

$basket->setCatalogue($catalogue);

$basket->addDeliveryChargeRule(0, 50, 4.95);
$basket->addDeliveryChargeRule(50, 90, 2.95);

$basket->addSpecialOffer('R01', 2, 0.5);

$testCases = [
    ['B01', 'G01'],
    ['R01', 'R01'],
    ['R01', 'G01'],
    ['B01', 'B01', 'R01', 'R01', 'R01']
];

foreach ($testCases as $testCase) {
    foreach ($testCase as $productCode) {
        $basket->add($productCode);
    }

    echo implode(', ', $testCase) . " (Total: $" . $basket->getTotal() . ")\n";

    $basket->clearBasket();
}