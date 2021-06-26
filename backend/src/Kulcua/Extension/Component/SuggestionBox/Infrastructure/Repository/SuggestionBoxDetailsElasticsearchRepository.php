<?php

namespace Kulcua\Extension\Component\SuggestionBox\Infrastructure\Repository;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use OpenLoyalty\Component\Core\Infrastructure\Repository\OloyElasticsearchRepository;
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetails;
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetailsRepository;

/**
 * Class SuggestionBoxDetailsElasticsearchRepository.
 */
class SuggestionBoxDetailsElasticsearchRepository extends OloyElasticsearchRepository implements SuggestionBoxDetailsRepository
{
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
