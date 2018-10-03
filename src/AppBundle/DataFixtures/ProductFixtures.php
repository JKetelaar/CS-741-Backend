<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProductFixtures
 * @package AppBundle\DataFixtures
 */
class ProductFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * Defines how many
     */
    const IMAGE_AMOUNT = 10;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $directory = $this->container->getParameter('upload_directory');

        $randomImages = $this->generateImagesArray($faker, $directory);

        for ($i = 0; $i < 20; $i++) {
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

            $imageKeys = array_rand($randomImages, $faker->numberBetween(2, self::IMAGE_AMOUNT));
            $images = [];
            foreach ($imageKeys as $imageKey) {
                $productImage = new ProductImage();
                $productImage->setProduct($product);
                $productImage->setFilename(basename($randomImages[$imageKey]));

                $manager->persist($productImage);
            }
            $product->setImages($images);

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function generateImagesArray($faker, $directory): array
    {
        $images = [];
        for ($j = 0; $j < self::IMAGE_AMOUNT; $j++) {
            $image = $faker->image('/tmp');
            $filename = md5($image).'.jpg';

            rename($image, $directory.$filename);

            $images[] = $directory.$filename;
        }

        return $images;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}