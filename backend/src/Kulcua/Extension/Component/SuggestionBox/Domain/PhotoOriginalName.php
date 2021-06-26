<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use Kulcua\Extension\Component\SuggestionBox\Domain\Exception\EmptyPhotoOriginalNameException;

/**
 * Class PhotoPath.
 */
class PhotoOriginalName
{
    /**
     * @var string
     */
    private $value;

    /**
     * PhotoPath constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        if (empty($name)) {
            throw EmptyPhotoOriginalNameException::create();
        }

        $this->value = $name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
