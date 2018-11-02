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
}
