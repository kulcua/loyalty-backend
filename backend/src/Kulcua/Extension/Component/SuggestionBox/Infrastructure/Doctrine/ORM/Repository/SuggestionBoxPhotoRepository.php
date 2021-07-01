<?php

namespace Kulcua\Extension\Component\SuggestionBox\Infrastructure\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Entity\SuggestionBoxPhoto;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Repository\SuggestionBoxPhotoRepositoryInterface;

/**
 * Class SuggestionBoxPhotoRepository.
 */
class SuggestionBoxPhotoRepository implements SuggestionBoxPhotoRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SuggestionBoxPhotoRepository constructor.
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
    public function save(SuggestionBoxPhoto $photo): void
    {
        $this->entityManager->persist($photo);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(SuggestionBoxPhoto $photo): void
    {
        $this->entityManager->remove($photo);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdSuggestionBoxId(PhotoId $photoId, SuggestionBoxId $suggestionBoxId): ?SuggestionBoxPhoto
    {
        return $this
            ->entityManager
            ->getRepository(SuggestionBoxPhoto::class)
            ->findOneBy(['suggestionBoxId' => $suggestionBoxId, 'photoId' => $photoId]);
    }
}
