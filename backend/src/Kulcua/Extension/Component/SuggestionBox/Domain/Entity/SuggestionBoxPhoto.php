<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Entity;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoMimeType;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoPath;

/**
 * Class SuggestionBoxPhoto.
 */
class SuggestionBoxPhoto
{
    /**
     * @var PhotoId
     */
    private $photoId;

    /**
     * @var SuggestionBoxId
     */
    private $suggestionBoxId;

    /**
     * @var PhotoPath
     */
    private $path;

    /**
     * @var PhotoOriginalName
     */
    private $originalName;

    /**
     * @var PhotoMimeType
     */
    private $mimeType;

    /**
     * SuggestionBoxPhoto constructor.
     *
     * @param SuggestionBoxId          $suggestionBoxId
     * @param PhotoId           $photoId
     * @param PhotoPath         $path
     * @param PhotoOriginalName $originalName
     * @param PhotoMimeType     $mimeType
     */
    public function __construct(
        SuggestionBoxId $suggestionBoxId,
        PhotoId $photoId,
        PhotoPath $path,
        PhotoOriginalName $originalName,
        PhotoMimeType $mimeType
    ) {
        $this->photoId = $photoId;
        $this->suggestionBoxId = $suggestionBoxId;
        $this->path = $path;
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
    }

    /**
     * @return PhotoPath
     */
    public function getPath(): PhotoPath
    {
        return $this->path;
    }

    /**
     * @return PhotoMimeType
     */
    public function getMimeType(): PhotoMimeType
    {
        return $this->mimeType;
    }
}
