<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Event;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxEvent;

/**
 * Class SuggestionBoxWasCreated.
 */
class SuggestionBoxWasCreated extends SuggestionBoxEvent
{
    /**
     * @var array
     */
    protected $suggestionBoxData;

    /**
     * SuggestionBoxEvent constructor.
     *
     * @param SuggestionBoxId $suggestionBoxId
     * @param array         $suggestionBoxData
     */
    public function __construct(
        SuggestionBoxId $suggestionBoxId,
        array $suggestionBoxData
    ) {
        parent::__construct($suggestionBoxId);

        if (is_numeric($suggestionBoxData['timestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($suggestionBoxData['timestamp']);
            $suggestionBoxData['timestamp'] = $tmp;
        }

        $this->suggestionBoxData = $suggestionBoxData;
    }

    /**
     * @return array
     */
    public function getSuggestionBoxData()
    {
        return $this->suggestionBoxData;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $suggestionBoxData = $this->suggestionBoxData;

        return array_merge(parent::serialize(), [
            'suggestionBoxId' => $this->suggestionBoxId->__toString(),
            'suggestionBoxData' => $suggestionBoxData,
        ]);
    }

    public static function deserialize(array $data)
    {
        $suggestionBoxData = $data['suggestionBoxData'];

        return new self(
            new SuggestionBoxId($data['suggestionBoxId']),
            $suggestionBoxData
        );
    }
}
