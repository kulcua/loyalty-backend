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
    // protected $dynamicFields = [
    //     [
    //         'grossValue' => [
    //             'match' => 'grossValue',
    //             'mapping' => [
    //                 'type' => 'double',
    //             ],
    //         ],
    //     ],
    //     [
    //         'maker' => [
    //             'match' => 'maker',
    //             'match_mapping_type' => 'string',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'analyzer' => 'small_letters',
    //             ],
    //         ],
    //     ],
    //     [
    //         'category' => [
    //             'match' => 'category',
    //             'match_mapping_type' => 'string',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'analyzer' => 'small_letters',
    //             ],
    //         ],
    //     ],
    //     [
    //         'label_value' => [
    //             'path_match' => 'items.labels.*',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'analyzer' => 'small_letters',
    //             ],
    //         ],
    //     ],
    //     [
    //         'maintenance_label_value' => [
    //             'path_match' => 'labels.*',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'analyzer' => 'small_letters',
    //             ],
    //         ],
    //     ],
    //     [
    //         'document_number_raw' => [
    //             'match' => 'documentNumberRaw',
    //             'match_mapping_type' => 'string',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'index' => 'not_analyzed',
    //             ],
    //         ],
    //     ],
    //     [
    //         'revised_document' => [
    //             'match' => 'revisedDocument',
    //             'match_mapping_type' => 'string',
    //             'mapping' => [
    //                 'type' => 'string',
    //                 'index' => 'not_analyzed',
    //             ],
    //         ],
    //     ],
    // ];

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
    public function findByWarrantyCenter(string $warrantyCenter, bool $customer = false): ?MaintenanceDetails
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
    public function findByActive(bool $active, bool $customer = true): array
    {
        $query['bool']['must'][]['term'] = ['active' => $active];

        if ($customer) {
            $query['bool']['must'][]['exists'] = ['field' => 'customerId'];
        }

        return $this->query($query);
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function countTotal(array $params = [], $exact = true): int
    // {
    //     $params = $this->prepareLabels($params);

    //     return parent::countTotal($params, $exact);
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function getAvailableLabels(): array
    // {
    //     $query = array(
    //         'index' => $this->index,
    //         'body' => array(
    //             'aggregations' => [
    //                 'labels_key' => [
    //                     'terms' => ['field' => 'items.labels.key'],
    //                     'aggregations' => [
    //                         'label_values' => [
    //                             'terms' => [
    //                                 'field' => 'items.labels.value',
    //                             ],
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ),
    //         'size' => 0,
    //     );

    //     try {
    //         $result = $this->client->search($query);
    //     } catch (Missing404Exception $e) {
    //         return [];
    //     }

    //     if (!array_key_exists('aggregations', $result)) {
    //         return [];
    //     }

    //     if (!array_key_exists('labels_key', $result['aggregations'])) {
    //         return [];
    //     }
    //     $labels = [];
    //     $labelKeys = $result['aggregations']['labels_key'];

    //     foreach ($labelKeys['buckets'] as $bucket) {
    //         $labels[$bucket['key']] = $this->getLabelValuesForBucket($bucket['label_values']);
    //     }

    //     return $labels;
    // }

    // /**
    //  * @param array $values
    //  *
    //  * @return array
    //  */
    // protected function getLabelValuesForBucket(array $values): array
    // {
    //     $val = [];
    //     foreach ($values['buckets'] as $bucket) {
    //         $val[] = $bucket['key'];
    //     }

    //     return $val;
    // }

    // /**
    //  * @param array $params
    //  *
    //  * @return array
    //  */
    // private function prepareLabels(array $params): array
    // {
    //     if (isset($params['labels'])) {
    //         $labelsFilter = $params['labels'];
    //         unset($params['labels']);

    //         foreach ($labelsFilter as $label) {
    //             $fields = [];
    //             if (empty($label['key']) && empty($label['value'])) {
    //                 continue;
    //             }
    //             if (!empty($label['key'])) {
    //                 $fields['labels.key'] = $label['key'];
    //             }
    //             if (!empty($label['value'])) {
    //                 $fields['labels.value'] = $label['value'];
    //             }
    //             $params[] = [
    //                 'type' => 'multiple_all',
    //                 'exact' => true,
    //                 'fields' => $fields,
    //             ];
    //         }
    //     }

    //     return $params;
    // }
}
