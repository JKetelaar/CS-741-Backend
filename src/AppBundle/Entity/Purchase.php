<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use AppBundle\Service\TaxCalculator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PurchaseRepository")
 */
class Purchase
{
    const STATE_COMPLETE = 'complete';
    const STATE_AWAITING_PAYMENT = 'awaiting_payment';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"default"})
     */
    private $id;

    /**
     * @var OrderItem[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderItem", mappedBy="purchase", orphanRemoval=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $products;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="purchases")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $user;

    /**
     * @var Promotion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Promotion", inversedBy="purchases")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $promotion;

    /**
     * @var string
     *
     * @ORM\Column(name="guest_id", type="string", length=255, nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $guestId;

    /**
     * @var OrderAddress
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrderAddress", inversedBy="billingPurchases")
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     *
     * @Serializer\Groups({"default"})
     */
    private $billingAddress;

    /**
     * @var OrderAddress
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrderAddress", inversedBy="shippingPurchases")
     * @ORM\JoinColumn(name="shipping_address_id", referencedColumnName="id")
     *
     * @Serializer\Groups({"default"})
     */
    private $shippingAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @Serializer\Groups({"default"})
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $state;

    /**
     * Purchase constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->state = self::STATE_AWAITING_PAYMENT;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Purchase
     */
    public function setUser(?User $user): Purchase
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getGuestId(): ?string
    {
        return $this->guestId;
    }

    /**
     * @param string $guestId
     *
     * @return Purchase
     */
    public function setGuestId(?string $guestId): Purchase
    {
        $this->guestId = $guestId;

        return $this;
    }

    /**
     * @return OrderAddress
     */
    public function getBillingAddress(): ?OrderAddress
    {
        return $this->billingAddress;
    }

    /**
     * @param OrderAddress $billingAddress
     *
     * @return Purchase
     */
    public function setBillingAddress(OrderAddress $billingAddress): Purchase
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return Purchase
     */
    public function setDate(\DateTime $date): Purchase
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return Purchase
     */
    public function setState(string $state): Purchase
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Purchase
     */
    public function setStateComplete(): Purchase
    {
        $this->state = self::STATE_COMPLETE;

        return $this;
    }

    /**
     * @return float
     *
     * @Serializer\Groups({"default"})
     */
    public function getFinalPrice()
    {
        $total = $this->getFinalPriceWithoutTax(false);

        if ($this->getShippingAddress() !== null) {
            $total += $this->getTax(false);
        }

        return number_format(round($total, 2), 2, '.', ',');
    }

    /**
     * @param bool $round
     * @return float
     *
     * @Serializer\Groups({"default"})
     */
    public function getFinalPriceWithoutTax(bool $round = true)
    {
        $total = 0.0;

        foreach ($this->getProducts() as $product) {
            $total += $product->getPrice() * $product->getQuantity();
        }

        if ($this->getPromotion() !== null) {
            $total -= ($total / 100 * $this->getPromotion()->getPercentage());
        }

        if ($round) {
            return number_format(round($total, 2), 2, '.', ',');
        } else {
            return $total;
        }
    }

    /**
     * @return OrderItem[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param OrderItem[] $products
     *
     * @return Purchase
     */
    public function setProducts(array $products): Purchase
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return Promotion
     */
    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    /**
     * @param Promotion $promotion
     *
     * @return Purchase
     */
    public function setPromotion(?Promotion $promotion): Purchase
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return OrderAddress
     */
    public function getShippingAddress(): ?OrderAddress
    {
        return $this->shippingAddress;
    }

    /**
     * @param OrderAddress $shippingAddress
     *
     * @return Purchase
     */
    public function setShippingAddress(OrderAddress $shippingAddress): Purchase
    {

        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * @param bool $round
     * @return float
     *
     * @Serializer\Groups({"default"})
     */
    public function getTax(bool $round = true)
    {
        return TaxCalculator::calculateTax(
            $this->getFinalPriceWithoutTax(false),
            $this->getShippingAddress()->getState(),
            $round
        );
    }
}
