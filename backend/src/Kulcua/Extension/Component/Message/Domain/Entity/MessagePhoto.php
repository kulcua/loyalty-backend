<?php

namespace Kulcua\Extension\Component\Message\Domain\Entity;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Kulcua\Extension\Component\Message\Domain\PhotoMimeType;
use Kulcua\Extension\Component\Message\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\Message\Domain\PhotoPath;

/**
 * Class MessagePhoto.
 */
class MessagePhoto
{
    /**
     * @var PhotoId
     */
    private $photoId;

    /**
     * @var MessageId
     */
    private $messageId;

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
     * MessagePhoto constructor.
     *
     * @param MessageId          $messageId
     * @param PhotoId           $photoId
     * @param PhotoPath         $path
     * @param PhotoOriginalName $originalName
     * @param PhotoMimeType     $mimeType
     */
    public function __construct(
        MessageId $messageId,
        PhotoId $photoId,
        PhotoPath $path,
        PhotoOriginalName $originalName,
        PhotoMimeType $mimeType
    ) {
        $this->photoId = $photoId;
        $this->messageId = $messageId;
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
