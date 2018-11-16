<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

/**
 * Class TaxCalculator
 * @package AppBundle\Service
 */
class TaxCalculator
{
    const TAX = [
        'CO' => 2.9,
        'IA' => 6.0,
        'WI' => 5.0,
    ];
    const FALLBACK_TAX = 5.5;

    /**
     * @param float $price
     * @param string $state
     * @return float
     */
    public function calculateTax(float $price, string $state): float
    {
        $tax = $this->getTaxForState($state);
        $taxPrice = $price / 100 * $tax;

        return round($price + $taxPrice, 2);
    }

    /**
     * @param string $state
     * @return float
     */
    public function getTaxForState(string $state): float
    {
        if (isset(self::TAX[$state])) {
            $tax = self::TAX[$state];
        } else {
            $tax = self::FALLBACK_TAX;
        }

        return $tax;
    }
}