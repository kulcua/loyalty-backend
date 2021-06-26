<?php

namespace Kulcua\Extension\Component\SuggestionBox\Infrastructure\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Repository\SuggestionBoxRepositoryInterface;

/**
 * Class SuggestionBoxRepository.
 */
class SuggestionBoxRepository implements SuggestionBoxRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SuggestionBoxRepository constructor.
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
    public function findOneById(SuggestionBoxId $suggestionBoxId): ?SuggestionBox
    {
        $suggestionBox = $this->entityManager->getRepository(SuggestionBox::class)->byId($suggestionBoxId);

        return $suggestionBox;
    }
}
