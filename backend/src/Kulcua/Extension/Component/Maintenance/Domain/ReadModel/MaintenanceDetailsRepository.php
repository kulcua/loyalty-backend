<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\ReadModel;

use Broadway\ReadModel\Repository;

interface MaintenanceDetailsRepository extends Repository
{
    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @param bool      $onlyWithCustomers
     *
     * @return MaintenanceDetails[]
     */
    public function findInPeriod(\DateTime $from, \DateTime $to, $onlyWithCustomers = true): array;

    /**
     * @return MaintenanceDetails[]
     */
    public function findAllWithCustomer(): array;

    /**
     * @param string $productSku
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByProductSku(string $productSku, bool $withCustomer = true): array;

    /**
     * @param string $description
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByDescription(string $description, bool $withCustomer = true): array;

    /**
     * @param string $cost
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByCost(string $cost, bool $withCustomer = true): array;

    /**
     * @param string $paymentStatus
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByPaymentStatus(string $paymentStatus, bool $withCustomer = true): array;

    /**
     * @param string $bookingTime
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByBookingTime(string $bookingTime, bool $withCustomer = true): array;

    /**
     * @param string $warrantyCenter
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByWarrantyCenter(string $warrantyCenter, bool $withCustomer = true): array;

    /**
     * @param bool $active
     * @param bool  $withCustomer
     *
     * @return MaintenanceDetails[]
     */
    public function findByActive(bool $active, bool $withCustomer = true): array;

    /**
     * @param array $params
     * @param bool  $exact
     *
     * @return MaintenanceDetails[]
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
     * @return MaintenanceDetails[]
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
