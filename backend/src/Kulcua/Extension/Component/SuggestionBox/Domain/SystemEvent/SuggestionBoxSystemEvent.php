<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxSystemEvent.
 */
class SuggestionBoxSystemEvent
{
    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * SuggestionBoxSystemEvent constructor.
     *
     * @param SuggestionBoxId $suggestionBoxId
     */
    public function __construct(SuggestionBoxId $suggestionBoxId)
    {
        $this->suggestionBoxId = $suggestionBoxId;
    }

    /**
     * @return SuggestionBoxId
     */
    public function getSuggestionBoxId()
    {
        return $this->suggestionBoxId;
    }
}
