<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Exception;

/**
 * Class InvalidPhotoMimeTypeException.
 */
class InvalidPhotoMimeTypeException extends \DomainException
{
    /**
     * @param string $types
     *
     * @return InvalidPhotoMimeTypeException
     */
    public static function create(string $types): self
    {
        return new self(
            sprintf(
                'Given file has invalid mime type. Expected types: %s',
                $types
            )
        );
    }
}
