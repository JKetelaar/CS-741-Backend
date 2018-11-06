<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"default"})
     */
    protected $id;

    /**
     * @var Cart
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Cart", mappedBy="user")
     */
    private $cart;

    /**
     * @var OrderAddress[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderAddress", mappedBy="user", orphanRemoval=true)
     */
    private $addresses;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return parent::getId();
    }

    /**
     * @return Cart
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     *
     * @return User
     */
    public function setCart(Cart $cart): User
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     *
     * @Serializer\Groups({"default"})
     */
    public function getBillingAddress(): ?OrderAddress
    {
        if ($this->getAddresses() !== null && count($this->getAddresses()) > 0) {
            foreach ($this->getAddresses() as $address) {
                if ($address->getType() === OrderAddress::BILLING_TYPE) {
                    return $address;
                }
            }
        }

        return $this->getShippingAddress();
    }

    /**
     * @return OrderAddress[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param OrderAddress[] $addresses
     *
     * @return User
     */
    public function setAddresses(array $addresses): User
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     *
     * @Serializer\Groups({"default"})
     */
    public function getShippingAddress(): ?OrderAddress
    {
        if ($this->getAddresses() !== null && count($this->getAddresses()) > 0) {
            foreach ($this->getAddresses() as $address) {
                if ($address->getType() === OrderAddress::SHIPPING_TYPE) {
                    return $address;
                }
            }
        }

        return null;
    }
}