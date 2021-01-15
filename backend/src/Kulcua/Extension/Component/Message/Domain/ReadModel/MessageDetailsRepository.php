<?php

namespace Kulcua\Extension\Component\Message\Domain\ReadModel;

use Broadway\ReadModel\Repository;

interface MessageDetailsRepository extends Repository
{
    // /**
    //  * @param \DateTime $from
    //  * @param \DateTime $to
    //  * @param bool      $onlyWithCustomers
    //  *
    //  * @return MessageDetails[]
    //  */
    // public function findInPeriod(\DateTime $from, \DateTime $to, $onlyWithCustomers = true): array;

    // /**
    //  * @param string $productSku
    //  * @param bool  $withCustomer
    //  *
    //  * @return MessageDetails[]
    //  */
    // public function findByProductSku(string $productSku, bool $withCustomer = true): array;

    /**
     * @param array $params
     * @param bool  $exact
     *
     * @return MessageDetails[]
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
     * @return MessageDetails[]
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
