<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Purchase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\PersistentCollection;
use Swift_Mailer;
use Twig_Environment;

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
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $templating;

    /**
     * CartHelper constructor.
     * @param ObjectManager $entityManager
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $templating
     */
    public function __construct(ObjectManager $entityManager, Swift_Mailer $mailer, Twig_Environment $templating)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
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

    public function sendEmail(Purchase $purchase)
    {
        if ($purchase->getUser() !== null) {
            $message = (new \Swift_Message('Order #'.$purchase->getId()))
                ->setFrom('contact@cs741.test')
                ->setTo($purchase->getUser()->getEmail())
                ->setBody(
                    $this->templating->render(
                        'Emails/Purchase/complete.html.twig',
                        ['purchase' => $purchase]
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }
    }
}