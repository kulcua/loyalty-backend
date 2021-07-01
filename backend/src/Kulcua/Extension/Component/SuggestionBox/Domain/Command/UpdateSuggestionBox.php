<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class UpdateSuggestionBox.
 */
class UpdateSuggestionBox extends SuggestionBoxCommand
{
    /**
     * @var SuggestionBoxId
     */
    protected $id;

    /**
     * @var array
     */
    protected $suggestionboxData;

    /**
     * UpdateSuggestionBox constructor.
     *
     * @param SuggestionBoxId $suggestionboxId
     * @param array   $suggestionboxData
     */
    public function __construct(SuggestionBoxId $suggestionboxId, array $suggestionboxData)
    {
        parent::__construct($suggestionboxId);
        $this->suggestionboxData = $suggestionboxData;
    }

    /**
     * @return null|SuggestionBoxId
     */
    public function getId(): ?SuggestionBoxId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getSuggestionBoxData()
    {
        return $this->suggestionboxData;
    }
}
