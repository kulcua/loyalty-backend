<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Event;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxWasUpdated.
 */
class SuggestionBoxWasUpdated extends SuggestionBoxEvent
{
    protected $suggestionBoxData;

    public function __construct(SuggestionBoxId $suggestionBoxId, array $suggestionBoxData)
    {
        parent::__construct($suggestionBoxId);
        $this->suggestionBoxData = $suggestionBoxData;
    }

    public function serialize(): array
    {
        $data = $this->suggestionBoxData;

        return array_merge(parent::serialize(), array(
            'suggestionBoxData' => $data
        ));
    }

    public static function deserialize(array $data)
    {
        return new self(
            new SuggestionBoxId($data['suggestionBoxId']),
            $data['suggestionBoxData']
        );
    }

    /**
     * @return array
     */
    public function getSuggestionBoxData()
    {
        return $this->suggestionBoxData;
    }
}
