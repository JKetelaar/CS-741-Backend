<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

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
     * @var string
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderAddress", mappedBy="billingPurchase")
     *
     * @Serializer\Groups({"default"})
     */
    private $billingAddress;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderAddress", mappedBy="shippingPurchase")
     *
     * @Serializer\Groups({"default"})
     */
    private $shippingAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Purchase constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
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
     * @return string
     */
    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    /**
     * @param string $billingAddress
     *
     * @return Purchase
     */
    public function setBillingAddress(string $billingAddress): Purchase
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    /**
     * @param string $shippingAddress
     *
     * @return Purchase
     */
    public function setShippingAddress(string $shippingAddress): Purchase
    {
        $this->shippingAddress = $shippingAddress;

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
}
