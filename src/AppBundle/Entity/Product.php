<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float|null
     *
     * @ORM\Column(name="promo_price", type="float", nullable=true)
     */
    private $promoPrice;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="promo_from", type="datetime", nullable=true)
     */
    private $promoFrom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="promo_to", type="datetime", nullable=true)
     */
    private $promoTo;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var ProductImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductImage", mappedBy="product")
     */
    private $images;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->active = true;
        $this->creationDate = new \DateTime();
        $this->images = [];
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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get promoPrice.
     *
     * @return float|null
     */
    public function getPromoPrice()
    {
        return $this->promoPrice;
    }

    /**
     * Set promoPrice.
     *
     * @param float|null $promoPrice
     *
     * @return Product
     */
    public function setPromoPrice($promoPrice = null)
    {
        $this->promoPrice = $promoPrice;

        return $this;
    }

    /**
     * Get promoFrom.
     *
     * @return \DateTime|null
     */
    public function getPromoFrom()
    {
        return $this->promoFrom;
    }

    /**
     * Set promoFrom.
     *
     * @param \DateTime|null $promoFrom
     *
     * @return Product
     */
    public function setPromoFrom($promoFrom = null)
    {
        $this->promoFrom = $promoFrom;

        return $this;
    }

    /**
     * Get promoTo.
     *
     * @return \DateTime|null
     */
    public function getPromoTo()
    {
        return $this->promoTo;
    }

    /**
     * Set promoTo.
     *
     * @param \DateTime|null $promoTo
     *
     * @return Product
     */
    public function setPromoTo($promoTo = null)
    {
        $this->promoTo = $promoTo;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Product
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return Product
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return ProductImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ProductImage[] $images
     *
     * @return Product
     */
    public function setImages(array $images): Product
    {
        $this->images = $images;

        return $this;
    }
}
