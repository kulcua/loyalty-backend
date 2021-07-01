<?php

namespace Kulcua\Extension\Component\Message\Infrastructure\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Entity\MessagePhoto;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Kulcua\Extension\Component\Message\Domain\Repository\MessagePhotoRepositoryInterface;

/**
 * Class MessagePhotoRepository.
 */
class MessagePhotoRepository implements MessagePhotoRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessagePhotoRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(MessagePhoto $photo): void
    {
        $this->entityManager->persist($photo);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(MessagePhoto $photo): void
    {
        $this->entityManager->remove($photo);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdMessageId(PhotoId $photoId, MessageId $messageId): ?MessagePhoto
    {
        return $this
            ->entityManager
            ->getRepository(MessagePhoto::class)
            ->findOneBy(['messageId' => $messageId, 'photoId' => $photoId]);
    }
}
