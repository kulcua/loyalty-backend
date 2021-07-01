<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Event;

/**
 * Class SuggestionBoxPhotoSavedEvent.
 */
class SuggestionBoxPhotoSavedEvent
{
    const NAME = 'kc.suggestion_box.photo.saved';

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $realPath;

    /**
     * SuggestionBoxPhotoSavedEvent constructor.
     *
     * @param string $filePath
     * @param string $realPath
     */
    public function __construct(string $filePath, string $realPath)
    {
        $this->filePath = $filePath;
        $this->realPath = $realPath;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getRealPath(): string
    {
        return $this->realPath;
    }
}
