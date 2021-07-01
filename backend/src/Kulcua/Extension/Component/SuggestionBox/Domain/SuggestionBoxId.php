<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class SuggestionBoxId.
 */
class SuggestionBoxId implements Identifier
{
    private $suggestionBoxId;

    /**
     * @param string $suggestionBoxId
     */
    public function __construct($suggestionBoxId)
    {
        Assert::string($suggestionBoxId);
        Assert::uuid($suggestionBoxId);

        $this->suggestionBoxId = $suggestionBoxId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->suggestionBoxId;
    }
}
