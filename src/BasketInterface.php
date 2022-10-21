<?php

namespace Acme;

interface BasketInterface
{
    /**
     * Add product in Basket
     * 
     * @param string $code
     * 
     * @return Basket
     */
    function add(string $code): Basket;

    /**
     * Get total cost of the Basket
     * 
     * @return float
     */
    function getTotal(): float;
}