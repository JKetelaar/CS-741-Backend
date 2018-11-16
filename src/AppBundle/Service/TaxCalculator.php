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
     * @param bool $round
     * @return float
     */
    public static function calculateWithTax(float $price, string $state, bool $round = true): float
    {
        $total = $price + self::calculateTax($price, $state, $round);

        if ($round) {
            return round($total, 2);
        } else {
            return $total;
        }
    }

    /**
     * @param float $price
     * @param string $state
     * @param bool $round
     * @return float
     */
    public static function calculateTax(float $price, string $state, bool $round = true): float
    {
        $tax = self::getTaxForState($state);
        $taxPrice = $price / 100 * $tax;

        if ($round) {
            return round($taxPrice, 2);
        } else {
            return $taxPrice;
        }
    }

    /**
     * @param string $state
     * @return float
     */
    public static function getTaxForState(string $state): float
    {
        if (isset(self::TAX[$state])) {
            $tax = self::TAX[$state];
        } else {
            $tax = self::FALLBACK_TAX;
        }

        return $tax;
    }
}