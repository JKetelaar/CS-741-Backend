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
     */
    protected $id;

    /**
     * @var OrderAddress[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderAddress", mappedBy="user")
     */
    protected $addresses;

    public function __construct()
    {
        parent::__construct();

        $this->roles = ['ROLE_USER'];
    }

    /**
     * @return mixed
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    public function getEmail(): string
    {
        return $this->email;
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
     * @return OrderAddress|null
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    public function getShippingAddress(): ?OrderAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->getType() === OrderAddress::SHIPPING_TYPE) {
                return $address;
            }
        }

        return null;
    }

    /**
     * @return OrderAddress|null
     *
     * @Serializer\Groups({"default", "minimal"})
     */
    public function getBillingAddress(): ?OrderAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->getType() === OrderAddress::BILLING_TYPE) {
                return $address;
            }
        }

        return null;
    }
}