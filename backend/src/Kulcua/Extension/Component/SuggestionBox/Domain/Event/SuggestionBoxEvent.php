<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Event;

use Broadway\Serializer\Serializable;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxEvent.
 */
abstract class SuggestionBoxEvent implements Serializable
{
    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * SuggestionBoxEvent constructor.
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'suggestionBoxId' => $this->suggestionBoxId->__toString(),
        ];
    }
}