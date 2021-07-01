<?php

namespace Kulcua\Extension\Component\Message\Domain\Event;

/**
 * Class MessagePhotoSavedEvent.
 */
class MessagePhotoSavedEvent
{
    const NAME = 'kc.message.photo.saved';

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $realPath;

    /**
     * MessagePhotoSavedEvent constructor.
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
