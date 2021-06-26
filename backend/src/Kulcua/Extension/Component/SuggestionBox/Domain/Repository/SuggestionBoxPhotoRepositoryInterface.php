<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Repository;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Entity\SuggestionBoxPhoto;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;

/**
 * Class SuggestionBoxPhotoRepository.
 */
interface SuggestionBoxPhotoRepositoryInterface
{
    /**
     * @param SuggestionBoxPhoto $photo
     */
    public function save(SuggestionBoxPhoto $photo): void;

    /**
     * @param SuggestionBoxPhoto $photo
     */
    public function remove(SuggestionBoxPhoto $photo): void;

    /**
     * @param PhotoId    $photoId
     * @param SuggestionBoxId $suggestionBoxId
     *
     * @return null|SuggestionBoxPhoto
     */
    public function findOneByIdSuggestionBoxId(PhotoId $photoId, SuggestionBoxId $suggestionBoxId): ?SuggestionBoxPhoto;
}
