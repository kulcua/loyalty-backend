<?php

namespace Kulcua\Extension\Component\Maintenance\Infrastructure\Repository;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use OpenLoyalty\Component\Core\Infrastructure\Repository\OloyElasticsearchRepository;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetails;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetailsRepository;

/**
 * Class MaintenanceDetailsElasticsearchRepository.
 */
class MaintenanceDetailsElasticsearchRepository extends OloyElasticsearchRepository implements MaintenanceDetailsRepository
{
    /**
     * {@inheritdoc}
     */
    public function findInPeriod(\DateTime $from, \DateTime $to, $onlyWithCustomers = true): array
    {
        $filter = [];
        $filter[] = ['range' => [
            'bookingDate' => [
                'gte' => $from->getTimestamp(),
                'lte' => $to->getTimestamp(),
            ],
        ]];
        $query = array(
            'bool' => array(
                'must' => [[
                    'bool' => [
                        'should' => $filter,
                    ],
                ]],
            ),
        );

        if ($onlyWithCustomers) {
            $query['bool']['must'][]['exists'] = ['field' => 'customerId'];
        }

        return $this->query($query);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllWithCustomer(): array
    {
        $query = array(
            'bool' => array(
                'must' => array(
                    'exists' => ['field' => 'customerId'],
                ),
            ),
        );

        return $this->query($query);
    }

    /**
     * {@inheritdoc}
     */
    public function findByProductSku(string $productSku, $withCustomer = true): array
    {
        $query['bool']['must'][]['term'] = ['productSku' => $productSku];

        if ($customer) {
            $query['bool']['must'][]['exists'] = ['field' => 'customerId'];
        }

        $result = $this->query($query);

        return $result[0] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function findByParametersPaginated(
        array $params,
        $exact = true,
        $page = 1,
        $perPage = 10,
        $sortField = null,
        $direction = 'DESC'
    ): array {
        $params = $this->prepareLabels($params);

        return parent::findByParametersPaginated($params, $exact, $page, $perPage, $sortField, $direction);
    }

    /**
     * {@inheritdoc}
     */
    public function findByWarrantyCenter(string $warrantyCenter, bool $withCustomer = true): array
    {
        $query['bool']['must'][]['term'] = ['warrantyCenter' => $warrantyCenter];

        if ($customer) {
            $query['bool']['must'][]['exists'] = ['field' => 'customerId'];
        }

        $result = $this->query($query);

        return $result[0] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function findByActive(bool $active, bool $withCustomer = true): array
    {
        $query['bool']['must'][]['term'] = ['active' => $active];

        if ($customer) {
            $query['bool']['must'][]['exists'] = ['field' => 'customerId'];
        }

        return $this->query($query);
    }
}
