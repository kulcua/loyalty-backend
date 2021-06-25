<?php

namespace Kulcua\Extension\Component\Message\Domain\Repository;

use Kulcua\Extension\Component\Message\Domain\Message;
use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Interface MessageRepositoryInterface.
 */
interface MessageRepositoryInterface
{
    /**
     * @param MessageId $messageId
     *
     * @return Message|null
     */
    public function findOneById(MessageId $messageId): ?Message;
}
