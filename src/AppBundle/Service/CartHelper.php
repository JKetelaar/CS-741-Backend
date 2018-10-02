<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Entity\Cart;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

/**
 * Class CartHelper
 * @package AppBundle\Service
 */
class CartHelper
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * CartHelper constructor.
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

    /**
     * @param Product[] $products
     * @return OrderItem[]
     */
    public function convertToOrderItems(array $products): array
    {
        $ordersItems = [];
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $ordersItems[] = $this->convertToOrderItem($product);
            }
        }

        return $ordersItems;
    }

    /**
     * @param Product $product
     * @return OrderItem
     */
    public function convertToOrderItem(Product $product): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setProduct($product);
        $orderItem->setPrice($product->getPrice());
        $orderItem->setName($product->getName());

        return $orderItem;
    }

    /**
     * @param Cart $cart
     * @param Product $product
     * @return Cart
     */
    public function addProductToCart(Cart $cart, Product $product)
    {
        if (($orderItem = $cart->getOrderItem($product)) !== null) {
            $orderItem->setQuantity($orderItem->getQuantity() + 1);
        } else {
            $orderItem = $this->convertToOrderItem($product);
            $cart->addProduct($orderItem);

            $this->entityManager->persist($cart);
        }

        $this->entityManager->persist($orderItem);

        return $cart;
    }

    /**
     * Generates a new unique ID for the guest
     *
     * @return string
     */
    public function getUniqueGuestID()
    {
        $cartRepository = $this->entityManager->getRepository('AppBundle:Cart');

        $guestID = null;
        while ($guestID === null) {
            try {
                $guestID = random_bytes(25);
            } catch (\Exception $e) {
            }

            $cart = $cartRepository->findOneBy(['guestId' => $guestID]);
            if ($cart !== null) {
                $guestID = null;
            }
        }

        return md5($guestID);
    }
}