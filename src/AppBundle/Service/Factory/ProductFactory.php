<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service\Factory;

use AppBundle\Entity\Product;

/**
 * Class ProductFactory
 * @package AppBundle\Service\Factory
 */
class ProductFactory
{
    public static function createProduct(string $name, string $description, float $price, int $quantity)
    {
        $product = new Product();

        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setQuantity($quantity);

        return $product;
    }
}