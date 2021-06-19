<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Command;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;
use Assert\Assertion as Assert;

/**
 * Class BookWarranty.
 */
class BookWarranty extends WarrantyCommand
{
    /**
     * @var array
     */
    protected $warrantyData;

    /**
     * @var array
     */
    protected $customerData;

    private $requiredWarrantyFields = [
        'productSku',
        'bookingDate',
        'bookingTime',
        'warrantyCenter',
        'createdAt',
        'active'
    ];

    private $requiredCustomerFields = [
        'name',
        'email'
    ];

    public function __construct(
        WarrantyId $warrantyId,
        array $warrantyData,
        array $customerData
    ) {
        parent::__construct($warrantyId);
        foreach ($this->requiredWarrantyFields as $field) {
            Assert::keyExists($warrantyData, $field);
        }

        foreach ($this->requiredCustomerFields as $field) {
            Assert::keyExists($customerData, $field);
        }

        $this->warrantyData = $warrantyData;
        $this->customerData = $customerData;
    }

    /**
     * @return array
     */
    public function getWarrantyData()
    {
        return $this->warrantyData;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }
}
