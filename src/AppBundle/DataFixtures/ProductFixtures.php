<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use AppBundle\Service\Factory\ProductFactory;
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

        /*
         * Product 1 (shorts)
         */
        $shorts = ProductFactory::createProduct('shorts', 'These shorts are so awesome', 59.99, 10);
        if (rand(0, 2) === 1) {
            $shorts->setPromoPrice($faker->randomFloat(2, $shorts->getPrice() / 75, $shorts->getPrice() - 0.1));
            $shorts->setPromoFrom($faker->dateTime('now'));
            $shorts->setPromoTo($faker->dateTimeBetween('+10 hours', '+10 days'));
        }
        $shortsUrls = [
            'https://cdn.shopify.com/s/files/1/0077/0432/products/FlavorSavers_SWIM_M_LD_7_FRONTBCweb_600x.progressive.jpg?v=1534984976',
            'https://uniqlo.scene7.com/is/image/UNIQLO/goods_08_411693?$prod$',
            'https://www.rvca.com/media/transfer/img/mk202dae_ddn_1.jpg',
        ];
        foreach ($shortsUrls as $shortsUrl) {
            $shortsImage = $this->getImage($shortsUrl, $directory);

            $productImage = new ProductImage();
            $productImage->setProduct($shorts);
            $productImage->setFilename($shortsImage);

            $manager->persist($productImage);
        }
        $manager->persist($shorts);

        /*
         * Random products
         */
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

    /**
     * @param string $url
     * @param string $directory
     * @return string
     */
    private function getImage(string $url, string $directory): string
    {
        $filename = md5(time().$url).'.jpg';
        file_put_contents($directory.$filename, file_get_contents($url));

        return $filename;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}