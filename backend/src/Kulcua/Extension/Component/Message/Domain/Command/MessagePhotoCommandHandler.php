<?php

namespace Kulcua\Extension\Component\Message\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Event\MessagePhotoSavedEvent;
use Kulcua\Extension\Component\Message\Domain\Factory\PhotoEntityFactoryInterface;
use Kulcua\Extension\Component\Message\Domain\Repository\MessagePhotoRepositoryInterface;
use Kulcua\Extension\Component\Message\Domain\MessageRepository;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Kulcua\Extension\Component\Message\Domain\PhotoMimeType;
use Kulcua\Extension\Component\Message\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\Message\Domain\PhotoPath;

/**
 * Class MessagePhotoCommandHandler.
 */
class MessagePhotoCommandHandler extends SimpleCommandHandler
{
    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var MessagePhotoRepositoryInterface
     */
    private $photoRepository;

    /**
     * @var PhotoEntityFactoryInterface
     */
    private $photoEntityFactory;

    /**
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * MessageCommandHandler constructor.
     *
     * @param MessageRepository      $messageRepository
     * @param EventDispatcher                  $eventDispatcher
     * @param MessagePhotoRepositoryInterface $photoRepository
     * @param PhotoEntityFactoryInterface      $photoEntityFactory
     * @param UuidGeneratorInterface           $uuidGenerator
     */
    public function __construct(
        MessageRepository $messageRepository,
        EventDispatcher $eventDispatcher,
        MessagePhotoRepositoryInterface $photoRepository,
        PhotoEntityFactoryInterface $photoEntityFactory,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->messageRepository = $messageRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->photoRepository = $photoRepository;
        $this->photoEntityFactory = $photoEntityFactory;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param MessagePhotoCommand $command
     *
     * @throws \Assert\AssertionFailedException|\InvalidArgumentException
     */
    public function handleMessagePhotoCommand(MessagePhotoCommand $command): void
    {
        $messageId = new MessageId($command->getMessageId());
        $message = $this->messageRepository->load((string) $messageId);
        if (null === $message) {
            throw new \InvalidArgumentException('Message not found!');
        }

        $photoId = new PhotoId($command->getMessageId());
        // $photoId = new PhotoId($this->uuidGenerator->generate());

        $fileName = md5(uniqid()).'.'.$command->getFile()['extension'];

        $photoPath = new PhotoPath($fileName);
        $mimeType = new PhotoMimeType($command->getFile()['mime_type']);
        $originalName = new PhotoOriginalName($command->getFile()['original_name']);

        $photo = $this->photoEntityFactory->create($messageId, $photoId, $photoPath, $originalName, $mimeType);
        $this->photoRepository->save($photo);

        $this->eventDispatcher->dispatch(
            MessagePhotoSavedEvent::NAME,
            [
                'file_path' => (string) $photoPath,
                'real_path' => $command->getFile()['real_path'],
            ]
        );
    }
}
