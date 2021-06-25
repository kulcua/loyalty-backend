<?php

namespace Kulcua\Extension\Component\Message\Domain\Factory;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Entity\MessagePhoto;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Kulcua\Extension\Component\Message\Domain\PhotoMimeType;
use Kulcua\Extension\Component\Message\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\Message\Domain\PhotoPath;

/**
 * Class PhotoEntityFactory.
 */
class PhotoEntityFactory implements PhotoEntityFactoryInterface
{
    /**
     * @param MessageId         $messageId
     * @param PhotoId           $photoId
     * @param PhotoPath         $photoPath
     * @param PhotoOriginalName $originalName
     * @param PhotoMimeType     $mimeType
     *
     * @return MessagePhoto
     */
    public function create(
        MessageId $messageId,
        PhotoId $photoId,
        PhotoPath $photoPath,
        PhotoOriginalName $originalName,
        PhotoMimeType $mimeType
    ): MessagePhoto {
        return new MessagePhoto($messageId, $photoId, $photoPath, $originalName, $mimeType);
    }
}
