<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * OrderItem
 *
 * @ORM\Table(name="order_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderItemRepository")
 */
class OrderItem
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     *
     * @Serializer\Groups({"default"})
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     *
     * @Serializer\Groups({"default"})
     */
    private $quantity;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="orderItems")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *
     * @Serializer\Groups({"default"})
     */
    private $product;

    /**
     * @var Cart
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cart", inversedBy="products")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id", nullable=true)
     */
    private $cart;

    /**
     * @var Purchase
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Purchase", inversedBy="products")
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id", nullable=true)
     */
    private $purchase;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return OrderItem
     */
    public function setName(string $name): OrderItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return OrderItem
     */
    public function setPrice(float $price): OrderItem
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return OrderItem
     */
    public function setProduct(Product $product): OrderItem
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return OrderItem
     */
    public function setQuantity(int $quantity): OrderItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     *
     * @return OrderItem
     */
    public function setCart(Cart $cart): OrderItem
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return Purchase
     */
    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    /**
     * @param Purchase $purchase
     *
     * @return OrderItem
     */
    public function setPurchase(Purchase $purchase): OrderItem
    {
        $this->purchase = $purchase;

        return $this;
    }
}
