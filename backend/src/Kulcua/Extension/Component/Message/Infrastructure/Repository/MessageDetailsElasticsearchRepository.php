<?php

namespace Kulcua\Extension\Component\Message\Infrastructure\Repository;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use OpenLoyalty\Component\Core\Infrastructure\Repository\OloyElasticsearchRepository;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetails;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetailsRepository;

/**
 * Class MessageDetailsElasticsearchRepository.
 */
class MessageDetailsElasticsearchRepository extends OloyElasticsearchRepository implements MessageDetailsRepository
{
    protected $dynamicFields = [
        [
            'conversationParticipantIds' => [
                'match' => 'conversationParticipantIds',
                'match_mapping_type' => 'string',
                'mapping' => [
                    'type' => 'string',
                    'index' => 'not_analyzed',
                ],
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function findByParametersPaginated(
        array $params,
        $exact = true,
        $page = 1,
        $perPage = 10,
        $sortField = null,
        $direction = 'ASC'
    ): array {
        return parent::findByParametersPaginated($params, $exact, $page, $perPage, $sortField, $direction);
    }
}
