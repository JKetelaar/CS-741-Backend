<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PromotionFixtures
 * @package AppBundle\DataFixtures
 */
class PromotionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $promotion = new Promotion();
        $promotion->setCode('NOV18');
        $promotion->setExpirationDate(new \DateTime('Nov.	16,	2018 23:59:59'));
        $promotion->setPercentage(20);

        $manager->persist($promotion);
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
            CartFixtures::class,
        ];
    }
}