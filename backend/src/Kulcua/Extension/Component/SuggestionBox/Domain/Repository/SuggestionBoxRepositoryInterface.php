<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Repository;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Interface SuggestionBoxRepositoryInterface.
 */
interface SuggestionBoxRepositoryInterface
{
    /**
     * @param SuggestionBoxId $suggestionBoxId
     *
     * @return SuggestionBox|null
     */
    public function findOneById(SuggestionBoxId $suggestionBoxId): ?SuggestionBox;
}
