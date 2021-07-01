<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Assert\Assertion as Assert;

/**
 * Class CreateSuggestionBox.
 */
class CreateSuggestionBox extends SuggestionBoxCommand
{
    /**
     * @var array
     */
    protected $suggestionBoxData;

    private $requiredSuggestionBoxFields = [
        'senderId',
        'senderName',
        'problemType',
        'description',
        'active',
        'photo',
        'timestamp'
    ];

    public function __construct(
        SuggestionBoxId $suggestionBoxId,
        array $suggestionBoxData
    ) {
        parent::__construct($suggestionBoxId);
        foreach ($this->requiredSuggestionBoxFields as $field) {
            Assert::keyExists($suggestionBoxData, $field);
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
}
