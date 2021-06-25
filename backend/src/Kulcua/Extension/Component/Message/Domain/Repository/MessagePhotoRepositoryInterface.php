<?php

namespace Kulcua\Extension\Component\Message\Domain\Repository;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Entity\MessagePhoto;
use Kulcua\Extension\Component\Message\Domain\PhotoId;

/**
 * Class MessagePhotoRepository.
 */
interface MessagePhotoRepositoryInterface
{
    /**
     * @param MessagePhoto $photo
     */
    public function save(MessagePhoto $photo): void;

    /**
     * @param MessagePhoto $photo
     */
    public function remove(MessagePhoto $photo): void;

    /**
     * @param PhotoId    $photoId
     * @param MessageId $messageId
     *
     * @return null|MessagePhoto
     */
    public function findOneByIdMessageId(PhotoId $photoId, MessageId $messageId): ?MessagePhoto;
}
