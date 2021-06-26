<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Factory;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Entity\SuggestionBoxPhoto;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoMimeType;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoPath;

/**
 * Class PhotoEntityFactoryInterface.
 */
interface PhotoEntityFactoryInterface
{
    /**
     * @param SuggestionBoxId         $suggestionBoxId
     * @param PhotoId           $photoId
     * @param PhotoPath         $photoPath
     * @param PhotoOriginalName $originalName
     * @param PhotoMimeType     $mimeType
     *
     * @return SuggestionBoxPhoto
     */
    public function create(
        SuggestionBoxId $suggestionBoxId,
        PhotoId $photoId,
        PhotoPath $photoPath,
        PhotoOriginalName $originalName,
        PhotoMimeType $mimeType
    ): SuggestionBoxPhoto;
}
