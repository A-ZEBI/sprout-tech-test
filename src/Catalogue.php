<?php

namespace Acme;

class Catalogue
{
    protected array $products = [];

    /**
     * Add product in catalogue
     * 
     * @param Product $product
     * 
     * @return Catalogue
     */
    public function addProduct($product)
    {
        if ($product instanceof Product) {
            $this->products[$product->getCode()] = $product;
        }
        return $this;
    }

    /**
     * Get all products from catalogue
     * 
     * @return array
     */
    public function getAllProducts()
    {
        return $this->products;
    }

    /**
     * Get product by code
     * 
     * @return Product|null
     */
    public function get(string $code)
    {
        return $this->products[$code] ?? null;
    }

}