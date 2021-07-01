<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SuggestionBoxPhotoCommand.
 */
class SuggestionBoxPhotoCommand
{
    /**
     * @var array
     */
    private $file;

    /**
     * @var string
     */
    private $suggestionBoxId;

    /**
     * SuggestionBoxPhotoCommand constructor.
     *
     * @param array  $file
     * @param string $suggestionBoxId
     */
    public function __construct(array $file, string $suggestionBoxId)
    {
        $this->file = $file;
        $this->suggestionBoxId = $suggestionBoxId;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param SuggestionBoxId   $suggestionBoxId
     *
     * @return SuggestionBoxPhotoCommand
     */
    public static function withData(UploadedFile $uploadedFile, SuggestionBoxId $suggestionBoxId): self
    {
        $file = [
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getClientMimeType(),
            'path' => $uploadedFile->getPath(),
            'extension' => $uploadedFile->guessExtension(),
            'real_path' => $uploadedFile->getRealPath(),
        ];

        return new self($file, (string) $suggestionBoxId);
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
    public function getSuggestionBoxId(): string
    {
        return $this->suggestionBoxId;
    }
}
