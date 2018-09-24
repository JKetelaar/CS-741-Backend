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
            $product->setPrice($faker->randomFloat(2, 1, 150));
            $product->setDescription($faker->text);
            $manager->persist($product);
        }

        $manager->flush();
    }
}