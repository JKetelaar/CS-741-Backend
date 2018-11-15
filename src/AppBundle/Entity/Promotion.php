<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromotionRepository")
 */
class Promotion
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
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     *
     * @Serializer\Groups({"default"})
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime")
     *
     * @Serializer\Groups({"default"})
     */
    private $expirationDate;

    /**
     * @var float
     *
     * @ORM\Column(name="percentage", type="float")
     *
     * @Serializer\Groups({"default"})
     */
    private $percentage;

    /**
     * @var Cart[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cart", mappedBy="promotion", orphanRemoval=true)
     */
    private $carts;

    /**
     * @var Purchase[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Purchase", mappedBy="promotion", orphanRemoval=true)
     */
    private $purchases;

    /**
     * Promotion constructor.
     */
    public function __construct()
    {
        $this->expirationDate = new \DateTime();
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
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Promotion
     */
    public function setCode(string $code): Promotion
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     *
     * @return Promotion
     */
    public function setExpirationDate(\DateTime $expirationDate): Promotion
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    /**
     * @param float $percentage
     *
     * @return Promotion
     */
    public function setPercentage(float $percentage): Promotion
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * @return Cart[]
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * @param Cart[] $carts
     *
     * @return Promotion
     */
    public function setCarts(array $carts): Promotion
    {
        $this->carts = $carts;

        return $this;
    }

    /**
     * @return Purchase[]
     */
    public function getPurchases(): array
    {
        return $this->purchases;
    }

    /**
     * @param Purchase[] $purchases
     *
     * @return Promotion
     */
    public function setPurchases(array $purchases): Promotion
    {
        $this->purchases = $purchases;

        return $this;
    }
}