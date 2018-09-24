<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ProductFixtures
 * @package AppBundle\DataFixtures
 */
class ProductFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $faker = Factory::create();

            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->text);

            $product->setPrice($faker->randomFloat(2, 1, 150));

            if (rand(0, 2) === 1) {
                $product->setPromoPrice($faker->randomFloat(2, 1, $product->getPrice() - 0.1));
                $product->setPromoFrom($faker->dateTime('now'));
                $product->setPromoTo($faker->dateTimeBetween('+10 hours', '+10 days'));
            }

            $product->setQuantity($faker->numberBetween(1, 150));

            $manager->persist($product);
        }

        $manager->flush();
    }
}