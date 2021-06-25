<?php

namespace Kulcua\Extension\Component\Message\Domain;

use Kulcua\Extension\Component\Message\Domain\Exception\EmptyPhotoPathException;

/**
 * Class PhotoPath.
 */
class PhotoPath
{
    private const PHOTO_DIR = 'message_photos/';

    /**
     * @var string
     */
    private $value;

    /**
     * PhotoPath constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (empty($path)) {
            throw EmptyPhotoPathException::create();
        }

        $this->value = self::PHOTO_DIR.$path;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
