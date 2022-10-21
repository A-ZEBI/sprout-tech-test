<?php

namespace Acme;

class Basket implements BasketInterface
{
    protected Catalogue $catalogue;
    protected array $specialOffers = [];
    protected array $deliveryChargeRules = [];

    protected array $basketItems = [];

    /**
     * Add product in basket
     * 
     * @param string $code
     * 
     * @return Basket
     */
    public function add(string $code): Basket
    {
        $product = $this->getCatalogue()->get($code);

        if ($product = $this->catalogue->get($code)) {
            if (isset($this->basketItems[$code])) {
                $this->basketItems[$code]['quantity'] += 1;
            } else {
                $this->basketItems[$code] = [
                    "product" => $product,
                    "quantity" => 1
                ];
            }
        }

        return $this;
    }

    /**
     * Get total cost of the Basket
     * 
     * @return float
     */
    public function getTotal(): float
    {
        $subTotal = $this->getSubTotal();
        $discount = $this->calculateDiscount();
        $deliveryCost = $this->calculateDeliveryCost($subTotal - $discount);

        $total = $subTotal + $deliveryCost - $discount;

        return round($total, 2, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Get sub total of basket
     * 
     * @return float
     */
    public function getSubTotal()
    {
        $total = 0.0;
        foreach ($this->basketItems as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return round($total, 2);
    }

    /**
     * Calculate discount from special offers
     * 
     * @return float
     */
    public function calculateDiscount()
    {
        $discount = 0;
        foreach ($this->specialOffers as $offer) {
            if (isset($this->basketItems[$offer['productCode']])) {
                $item = $this->basketItems[$offer['productCode']];
                $discount += floor($item['quantity'] / $offer['step']) * ($item['product']->getPrice() * $offer['multiplier']);
            }
        }
        return $discount;
    }

    /**
     * Calculate delivery cost on total amount
     * 
     * @param float $subtotal
     * 
     * @return float
     */
    public function calculateDeliveryCost(float $subTotal)
    {
        $cost = 0;

        foreach ($this->deliveryChargeRules as $rule) {
            if ($rule['minTotal'] <= $subTotal && $subTotal < $rule['maxTotal']) {
                $cost = $rule['cost'];
                break;
            }
        }

        return $cost;
    }

    /**
     * Add special special offer
     * 
     * @param string $productCode
     * @param int $step
     * @param float $multiplier
     * 
     * @return Basket
     */
    public function addSpecialOffer(string $productCode, int $step, float $multiplier)
    {
        $this->specialOffers[] = [
            'productCode' => $productCode,
            'step' => $step,
            'multiplier' => $multiplier
        ];

        return $this;
    }

    /**
     * Get the value of catalogue
     *
     * @return Catalogue
     */
    protected function getCatalogue(): Catalogue
    {
        return $this->catalogue;
    }

    /**
     * Set the value of catalogue
     *
     * @param Catalogue $catalogue
     *
     * @return self
     */
    public function setCatalogue(Catalogue $catalogue): self
    {
        $this->catalogue = $catalogue;

        return $this;
    }

    /**
     * Get the value of deliveryChargeRules
     *
     * @return array
     */
    public function getDeliveryChargeRules(): array
    {
        return $this->deliveryChargeRules;
    }

    /**
     * Add delivery charge cost rule
     *
     * @param float $minTotal
     * @param float $maxTotal
     * @param float $cost
     *
     * @return self
     */
    public function addDeliveryChargeRule(?float $minTotal, ?float $maxTotal, float $cost)
    {
        $this->deliveryChargeRules[] = [
            'minTotal' => $minTotal,
            'maxTotal' => $maxTotal,
            'cost' => $cost
        ];
    }

    /**
     * Clear basket items
     * 
     * @return self
     */
    public function clearBasket()
    {
        $this->basketItems = [];

        return $this;
    }
}
