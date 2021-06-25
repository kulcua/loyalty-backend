<?php

namespace Kulcua\Extension\Component\Message\Domain\Exception;

/**
 * Class EmptyPhotoPathException.
 */
class EmptyPhotoPathException extends \DomainException
{
    /**
     * @return EmptyPhotoPathException
     */
    public static function create(): self
    {
        return new self('Photo path is required and can not be empty!');
    }
}
