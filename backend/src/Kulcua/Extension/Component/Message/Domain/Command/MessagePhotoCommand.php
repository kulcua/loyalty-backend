<?php

namespace Kulcua\Extension\Component\Message\Domain\Command;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MessagePhotoCommand.
 */
class MessagePhotoCommand
{
    /**
     * @var array
     */
    private $file;

    /**
     * @var string
     */
    private $messageId;

    /**
     * MessagePhotoCommand constructor.
     *
     * @param array  $file
     * @param string $messageId
     */
    public function __construct(array $file, string $messageId)
    {
        $this->file = $file;
        $this->messageId = $messageId;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param MessageId   $messageId
     *
     * @return MessagePhotoCommand
     */
    public static function withData(UploadedFile $uploadedFile, MessageId $messageId): self
    {
        $file = [
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getClientMimeType(),
            'path' => $uploadedFile->getPath(),
            'extension' => $uploadedFile->guessExtension(),
            'real_path' => $uploadedFile->getRealPath(),
        ];

        return new self($file, (string) $messageId);
    }

    /**
     * @return array
     */
    public function getFile(): array
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }
}
