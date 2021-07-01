<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxCommand.
 */
abstract class SuggestionBoxCommand
{
    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * SuggestionBoxCommand constructor.
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
