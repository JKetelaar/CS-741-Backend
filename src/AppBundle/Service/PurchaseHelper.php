<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Purchase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\PersistentCollection;

/**
 * Class PurchaseHelper
 * @package AppBundle\Service
 */
class PurchaseHelper
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
     * Converts a cart into a purchase (order)
     *
     * @param Cart $cart
     * @param Purchase|null $purchase
     * @return Purchase
     */
    public function cartToPurchase(Cart $cart, ?Purchase $purchase = null): Purchase
    {
        if ($purchase === null) {
            $purchase = new Purchase();
        }

        /** @var PersistentCollection $products */
        $products = $cart->getProducts();
        $purchase->setProducts($products->toArray());
        $purchase->setUser($cart->getUser());
        $purchase->setGuestId($cart->getGuestId());
        $purchase->setPromotion($cart->getPromotion());

        foreach ($purchase->getProducts() as $product) {
            $product->setPurchase($purchase);
        }

        return $purchase;
    }
}