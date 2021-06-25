<?php

namespace Kulcua\Extension\Component\Message\Domain\Exception;

/**
 * Class EmptyPhotoOriginalNameException.
 */
class EmptyPhotoOriginalNameException extends \DomainException
{
    /**
     * @return EmptyPhotoOriginalNameException
     */
    public static function create(): self
    {
        return new self('Photo original name is required and can not be empty!');
    }
}
