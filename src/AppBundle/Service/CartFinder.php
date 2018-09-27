<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Entity\Cart;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

/**
 * Class CartFinder
 * @package AppBundle\Service
 */
class CartFinder
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * CartFinder constructor.
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @return Cart|null
     */
    public function findCartForUserOrGuest(Request $request, ?User $user): ?Cart
    {
        $cartRepository = $this->entityManager->getRepository('AppBundle:Cart');
        $cart = null;

        if ($user !== null && $user instanceof User) {
            $cart = $cartRepository->findOneBy(['user' => $user]);
        } else {
            $cart = $cartRepository->findOneBy(['guestId' => $request->cookies->get('guestid')]);
        }

        return $cart;
    }
}