<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\OrderAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserFixtures
 * @package AppBundle\DataFixtures
 */
class UserFixtures extends Fixture implements ContainerAwareInterface
{
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
        $userManager = $this->container->get('fos_user.user_manager');

        /*
         * Create user
         */
        $user = $userManager->createUser();

        $email = 'user@email.com';
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setEmailCanonical($email);

        $user->setEnabled(1);
        $user->setPlainPassword('user');
        $user->setRoles(['ROLE_USER']);

        $shippingAddress = new OrderAddress();
        $shippingAddress->setUser($user);
        $shippingAddress->setAddress('1424 Vine Street');
        $shippingAddress->setCity('La Crosse');
        $shippingAddress->setFullname('Jeroen Ketelaar');
        $shippingAddress->setInstructions('Leave at frontdoor');
        $shippingAddress->setPhoneNumber('+1 651 440 7740');
        $shippingAddress->setSecondaryAddress('Apt. #2');
        $shippingAddress->setZipCode('54601');
        $shippingAddress->setState('WI');
        $shippingAddress->setType(OrderAddress::SHIPPING_TYPE);

        $billingAddress = clone $shippingAddress;
        $billingAddress->setType(OrderAddress::BILLING_TYPE);

        $manager->persist($user);
        $manager->persist($shippingAddress);
        $manager->persist($billingAddress);

        /*
         * Create admin
         */
        $admin = $userManager->createUser();

        $email = 'admin@email.com';
        $admin->setUsername($email);
        $admin->setEmail($email);
        $admin->setEmailCanonical($email);

        $admin->setEnabled(1);
        $admin->setPlainPassword('admin');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}