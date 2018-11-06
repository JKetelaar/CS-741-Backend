<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderItem", mappedBy="cart", orphanRemoval=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $products;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="cart")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $user;

    /**
     * @var Promotion
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Promotion", inversedBy="carts")
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Product $product
     * @return OrderItem|null
     */
    public function getOrderItem(Product $product)
    {
        if ($this->products !== null && count($this->products) > 0) {
            foreach ($this->products as $orderItem) {
                if ($product->getId() === $orderItem->getProduct()->getId()) {
                    return $orderItem;
                }
            }
        }

        return null;
    }

    public function addProduct(OrderItem $orderItem): Cart
    {
        if ($this->products instanceof ArrayCollection) {
            $this->products->add($orderItem);
        } else {
            $this->products[] = $orderItem;
        }

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
     * @return Cart
     */
    public function setUser(User $user): Cart
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
     * @return Cart
     */
    public function setGuestId(string $guestId): Cart
    {
        $this->guestId = $guestId;

        return $this;
    }

    /**
     * @return float
     *
     * @Serializer\Groups({"default"})
     */
    public function getFinalPrice()
    {
        $total = 0.0;

        foreach ($this->getProducts() as $product) {
            $total += $product->getPrice() * $product->getQuantity();
        }

        if ($this->getPromotion() !== null) {
            $total -= ($total / 100 * $this->getPromotion()->getPercentage());
        }

        return number_format(round($total, 2), 2, '.', '.');
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
     * @return Cart
     */
    public function setProducts(array $products): Cart
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
     * @return Cart
     */
    public function setPromotion(Promotion $promotion): Cart
    {
        $this->promotion = $promotion;

        return $this;
    }
}
