<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
     * @return OrderAddress[]
     */
    public function getAddresses(): array
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