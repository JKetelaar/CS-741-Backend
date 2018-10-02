<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

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
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    private $price;

    /**
     * @var float|null
     *
     * @ORM\Column(name="promo_price", type="float", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $promoPrice;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="promo_from", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $promoFrom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="promo_to", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $promoTo;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     *
     * @Serializer\Groups({"default"})
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     *
     * @Serializer\Groups({"default"})
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     *
     * @Serializer\Groups({"default"})
     */
    private $creationDate;

    /**
     * @var ProductImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductImage", mappedBy="product")
     *
     * @Serializer\Groups({"default"})
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
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPromoFrom(): ?\DateTime
    {
        return $this->promoFrom;
    }

    /**
     * @param \DateTime|null $promoFrom
     *
     * @return Product
     */
    public function setPromoFrom(?\DateTime $promoFrom): Product
    {
        $this->promoFrom = $promoFrom;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Product
     */
    public function setActive(bool $active): Product
    {
        $this->active = $active;

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
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     *
     * @return Product
     */
    public function setCreationDate(\DateTime $creationDate): Product
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

    /**
     * @return float
     *
     * @Serializer\Groups({"minimal"})
     */
    public function getFinalPrice(): float
    {
        return $this->hasPromo() ? $this->getPromoPrice() : $this->getPrice();
    }

    /**
     * @return bool
     *
     * @Serializer\Groups({"default"})
     */
    private function hasPromo(): bool
    {
        $now = new \DateTime();
        if ($this->getPromoPrice() != null) {
            if ($this->getPromoTo() >= $now) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return float|null
     */
    public function getPromoPrice(): ?float
    {
        return $this->promoPrice;
    }

    /**
     * @param float|null $promoPrice
     *
     * @return Product
     */
    public function setPromoPrice(?float $promoPrice): Product
    {
        $this->promoPrice = $promoPrice;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPromoTo(): ?\DateTime
    {
        return $this->promoTo;
    }

    /**
     * @param \DateTime|null $promoTo
     *
     * @return Product
     */
    public function setPromoTo(?\DateTime $promoTo): Product
    {
        $this->promoTo = $promoTo;

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
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Returns a single image if any can be found, otherwise it will return null
     *
     * @return ProductImage|null
     *
     * @Serializer\Groups({"minimal"})
     */
    public function getSingleImage(): ?ProductImage
    {
        if (count($this->images) > 0) {
            return $this->images[0];
        }

        return null;
    }
}
