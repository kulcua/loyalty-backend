<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class DeactivateSuggestionBox.
 */
class DeactivateSuggestionBox extends SuggestionBoxCommand
{
    protected $active;

    public function __construct(SuggestionBoxId $suggestionBoxId, $active)
    {
        parent::__construct($suggestionBoxId);
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->active;
    }
}
