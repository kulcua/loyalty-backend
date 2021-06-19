<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Model;

use Broadway\Serializer\Serializable;
use Assert\Assertion as Assert;

/**
 * Class CustomerBasicData.
 */
class CustomerBasicData implements Serializable
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $nip;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $loyaltyCardNumber;

    /**
     * @var CustomerAddress
     */
    protected $address;

    /**
     * CustomerBasicData constructor.
     *
     * @param string          $email
     * @param string          $name
     * @param string          $nip
     * @param string          $phone
     * @param string          $loyaltyCardNumber
     * @param CustomerAddress $address
     */
    public function __construct($email = null, $name, $nip = null, $phone = null, $loyaltyCardNumber = null, CustomerAddress $address)
    {
        Assert::notBlank($name);

        $this->email = $email;
        $this->name = $name;
        $this->nip = $nip;
        $this->phone = $phone;
        $this->loyaltyCardNumber = $loyaltyCardNumber;
        $this->address = $address;
    }

    /**
     * @param array $data
     *
     * @return CustomerBasicData
     */
    public static function deserialize(array $data)
    {
        return new self(
            isset($data['email']) ? $data['email'] : null,
            $data['name'],
            isset($data['nip']) ? $data['nip'] : null,
            isset($data['phone']) ? $data['phone'] : null,
            isset($data['loyaltyCardNumber']) ? $data['loyaltyCardNumber'] : null,
            CustomerAddress::deserialize($data['address'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'nip' => $this->nip,
            'phone' => $this->phone,
            'loyaltyCardNumber' => $this->loyaltyCardNumber,
            'address' => $this->address->serialize(),
        ];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getLoyaltyCardNumber()
    {
        return $this->loyaltyCardNumber;
    }

    /**
     * @return CustomerAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $email
     * @param string $phone
     */
    public function updateEmailAndPhone($email, $phone)
    {
        $this->email = $email;
        $this->phone = $phone;
    }
}
