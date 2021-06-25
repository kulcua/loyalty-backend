<?php

namespace Kulcua\Extension\Component\Message\Infrastructure\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Kulcua\Extension\Component\Message\Domain\Message;
use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Repository\MessageRepositoryInterface;

/**
 * Class MessageRepository.
 */
class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageRepository constructor.
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
    public function findOneById(MessageId $messageId): ?Message
    {
        $message = $this->entityManager->getRepository(Message::class)->byId($messageId);

        return $message;
    }
}
