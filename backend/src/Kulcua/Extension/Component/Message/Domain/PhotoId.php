<?php

namespace Kulcua\Extension\Component\Message\Domain;

use Webmozart\Assert\Assert;

/**
 * Class PhotoId.
 */
class PhotoId
{
    /**
     * @var string
     */
    private $id;

    /**
     * PhotoId constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
