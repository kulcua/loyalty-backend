<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxPhotoSavedEvent;
use Kulcua\Extension\Component\SuggestionBox\Domain\Factory\PhotoEntityFactoryInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\Repository\SuggestionBoxPhotoRepositoryInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxRepository;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoMimeType;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoOriginalName;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoPath;

/**
 * Class SuggestionBoxPhotoCommandHandler.
 */
class SuggestionBoxPhotoCommandHandler extends SimpleCommandHandler
{
    /**
     * @var SuggestionBoxRepository
     */
    private $suggestionBoxRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var SuggestionBoxPhotoRepositoryInterface
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
     * SuggestionBoxCommandHandler constructor.
     *
     * @param SuggestionBoxRepository      $suggestionBoxRepository
     * @param EventDispatcher                  $eventDispatcher
     * @param SuggestionBoxPhotoRepositoryInterface $photoRepository
     * @param PhotoEntityFactoryInterface      $photoEntityFactory
     * @param UuidGeneratorInterface           $uuidGenerator
     */
    public function __construct(
        SuggestionBoxRepository $suggestionBoxRepository,
        EventDispatcher $eventDispatcher,
        SuggestionBoxPhotoRepositoryInterface $photoRepository,
        PhotoEntityFactoryInterface $photoEntityFactory,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->suggestionBoxRepository = $suggestionBoxRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->photoRepository = $photoRepository;
        $this->photoEntityFactory = $photoEntityFactory;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param SuggestionBoxPhotoCommand $command
     *
     * @throws \Assert\AssertionFailedException|\InvalidArgumentException
     */
    public function handleSuggestionBoxPhotoCommand(SuggestionBoxPhotoCommand $command): void
    {
        $suggestionBoxId = new SuggestionBoxId($command->getSuggestionBoxId());
        $suggestionBox = $this->suggestionBoxRepository->load((string) $suggestionBoxId);
        if (null === $suggestionBox) {
            throw new \InvalidArgumentException('SuggestionBox not found!');
        }

        $photoId = new PhotoId($command->getSuggestionBoxId());
        // $photoId = new PhotoId($this->uuidGenerator->generate());

        $fileName = md5(uniqid()).'.'.$command->getFile()['extension'];

        $photoPath = new PhotoPath($fileName);
        $mimeType = new PhotoMimeType($command->getFile()['mime_type']);
        $originalName = new PhotoOriginalName($command->getFile()['original_name']);

        $photo = $this->photoEntityFactory->create($suggestionBoxId, $photoId, $photoPath, $originalName, $mimeType);
        $this->photoRepository->save($photo);

        $this->eventDispatcher->dispatch(
            SuggestionBoxPhotoSavedEvent::NAME,
            [
                'file_path' => (string) $photoPath,
                'real_path' => $command->getFile()['real_path'],
            ]
        );
    }
}
