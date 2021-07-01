<?php

namespace Kulcua\Extension\Component\Warranty\Domain\ReadModel;

use Broadway\ReadModel\Repository;

interface WarrantyDetailsRepository extends Repository
{
    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @param bool      $onlyWithCustomers
     *
     * @return WarrantyDetails[]
     */
    public function findInPeriod(\DateTime $from, \DateTime $to, $onlyWithCustomers = true): array;

    /**
     * @return WarrantyDetails[]
     */
    public function findAllWithCustomer(): array;

    /**
     * @param string $productSku
     * @param bool  $withCustomer
     *
     * @return WarrantyDetails[]
     */
    public function findByProductSku(string $productSku, bool $withCustomer = true): array;

    /**
     * @param string $bookingTime
     * @param bool  $withCustomer
     *
     * @return WarrantyDetails[]
     */
    public function findByBookingTime(string $bookingTime, bool $withCustomer = true): array;

    /**
     * @param string $warrantyCenter
     * @param bool  $withCustomer
     *
     * @return WarrantyDetails[]
     */
    public function findByWarrantyCenter(string $warrantyCenter, bool $withCustomer = true): array;

    /**
     * @param bool $active
     * @param bool  $withCustomer
     *
     * @return WarrantyDetails[]
     */
    public function findByActive(bool $active, bool $withCustomer = true): array;

    /**
     * @param array $params
     * @param bool  $exact
     *
     * @return WarrantyDetails[]
     */
    public function findByParameters(array $params, $exact = true): array;

    /**
     * @param array  $params
     * @param bool   $exact
     * @param int    $page
     * @param int    $perPage
     * @param null   $sortField
     * @param string $direction
     *
     * @return WarrantyDetails[]
     */
    public function findByParametersPaginated(array $params, $exact = true, $page = 1, $perPage = 10, $sortField = null, $direction = 'DESC'): array;

    /**
     * @param array $params
     * @param bool  $exact
     *
     * @return int
     */
    public function countTotal(array $params = [], $exact = true): int;
}
