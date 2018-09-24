<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var OrderItem[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\OrderItem")
     * @ORM\JoinTable(name="cart_order_items",
     *      joinColumns={@ORM\JoinColumn(name="cart_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="orderitem_id", referencedColumnName="id")}
     * )
     */
    private $products;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="user", type="object", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="guest_id", type="string", length=255, nullable=true)
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
    public function getProducts(): array
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
     * @return \stdClass
     */
    public function getUser(): \stdClass
    {
        return $this->user;
    }

    /**
     * @param \stdClass $user
     *
     * @return Cart
     */
    public function setUser(\stdClass $user): Cart
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getGuestId(): string
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
