<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * OrderAddress
 *
 * @ORM\Table(name="order_address")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderAddressRepository")
 */
class OrderAddress
{
    const SHIPPING_TYPE = 'shipping';
    const BILLING_TYPE = 'billing';

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
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="secondary_address", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $secondaryAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255)
     *
     * @Serializer\Groups({"default"})
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="text")
     *
     * @Serializer\Groups({"default"})
     */
    private $instructions;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="addresses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var Purchase[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Purchase", mappedBy="billingAddress")
     */
    private $billingPurchases;

    /**
     * @var Purchase[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Purchase", mappedBy="shippingAddress")
     */
    private $shippingPurchases;

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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return OrderAddress
     */
    public function setType(string $type): OrderAddress
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     *
     * @return OrderAddress
     */
    public function setFullname(string $fullname): OrderAddress
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return OrderAddress
     */
    public function setAddress(string $address): OrderAddress
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondaryAddress(): ?string
    {
        return $this->secondaryAddress;
    }

    /**
     * @param string $secondaryAddress
     *
     * @return OrderAddress
     */
    public function setSecondaryAddress(string $secondaryAddress): OrderAddress
    {
        $this->secondaryAddress = $secondaryAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return OrderAddress
     */
    public function setCity(string $city): OrderAddress
    {
        $this->city = $city;

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
     * @return OrderAddress
     */
    public function setState(string $state): OrderAddress
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return OrderAddress
     */
    public function setZipCode(string $zipCode): OrderAddress
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     *
     * @return OrderAddress
     */
    public function setPhoneNumber(string $phoneNumber): OrderAddress
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    /**
     * @param string $instructions
     *
     * @return OrderAddress
     */
    public function setInstructions(string $instructions): OrderAddress
    {
        $this->instructions = $instructions;

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
     * @return OrderAddress
     */
    public function setUser(User $user): OrderAddress
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Purchase[]
     */
    public function getBillingPurchases()
    {
        return $this->billingPurchases;
    }

    /**
     * @param Purchase[] $billingPurchases
     *
     * @return OrderAddress
     */
    public function setBillingPurchases(array $billingPurchases): OrderAddress
    {
        $this->billingPurchases = $billingPurchases;

        return $this;
    }

    /**
     * @return Purchase[]
     */
    public function getShippingPurchases()
    {
        return $this->shippingPurchases;
    }

    /**
     * @param Purchase[] $shippingPurchases
     *
     * @return OrderAddress
     */
    public function setShippingPurchases(array $shippingPurchases): OrderAddress
    {
        $this->shippingPurchases = $shippingPurchases;

        return $this;
    }
}
