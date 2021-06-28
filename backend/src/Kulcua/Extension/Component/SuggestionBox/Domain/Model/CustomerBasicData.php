<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Model;

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
    protected $phone;

    /**
     * @var string
     */
    protected $loyaltyCardNumber;

    /**
     * CustomerBasicData constructor.
     *
     * @param string          $email
     * @param string          $name
     * @param string          $phone
     * @param string          $loyaltyCardNumber
     */
    public function __construct($email = null, $name, $phone = null, $loyaltyCardNumber = null)
    {
        Assert::notBlank($name);

        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->loyaltyCardNumber = $loyaltyCardNumber;
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
            isset($data['phone']) ? $data['phone'] : null,
            isset($data['loyaltyCardNumber']) ? $data['loyaltyCardNumber'] : null
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
            'phone' => $this->phone,
            'loyaltyCardNumber' => $this->loyaltyCardNumber,
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
     * @param string $email
     * @param string $phone
     */
    public function updateEmailAndPhone($email, $phone)
    {
        $this->email = $email;
        $this->phone = $phone;
    }
}
