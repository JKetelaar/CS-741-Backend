<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Cart;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CartFixtures
 * @package AppBundle\DataFixtures
 */
class CartFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $productsRepository = $manager->getRepository('AppBundle:Product');

        $cart = new Cart();
        $cart->setGuestId('1');

        /* @var Product[] $products */
        $products = array_slice($productsRepository->findAll(), 0, 5);

        $orderItems = [];
        foreach ($products as $product) {
            $orderItem = new OrderItem();
            $orderItem->setName($product->getName());
            $orderItem->setPrice($product->getPrice());
            $orderItem->setProduct($product);

            $manager->persist($orderItem);

            $orderItems[] = $orderItem;
        }
        $cart->setProducts($orderItems);

        $manager->persist($cart);
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }
}