<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class CategoryFixtures
 * @package AppBundle\DataFixtures
 */
class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categories = [];
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setName($faker->word.' '.$faker->word);
            $category->setFilename($faker->md5);

            $categories[] = $category;
        }

        foreach ($manager->getRepository('AppBundle:Product')->findAll() as $product) {
            /** @var Category $category */
            $category = $categories[array_rand($categories, 1)];

            $product->setCategory($category);
            $category->addProduct($product);

            $manager->persist($category);
            $manager->persist($product);
        }

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