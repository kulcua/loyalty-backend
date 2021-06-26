<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use Kulcua\Extension\Component\SuggestionBox\Domain\Exception\EmptyPhotoPathException;

/**
 * Class PhotoPath.
 */
class PhotoPath
{
    private const PHOTO_DIR = 'suggestion_box_photos/';

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
