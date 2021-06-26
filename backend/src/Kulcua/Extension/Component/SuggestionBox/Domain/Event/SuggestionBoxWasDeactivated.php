<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Event;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxWasDeactivated.
 */
class SuggestionBoxWasDeactivated extends SuggestionBoxEvent
{
    /**
     * @var \DateTime
     */
    protected $deactivatedAt;

    public function __construct(SuggestionBoxId $suggestionBoxId)
    {
        parent::__construct($suggestionBoxId);
        $this->deactivatedAt = new \DateTime();
        $this->deactivatedAt->setTimestamp(time());
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), array(
            'deactivatedAt' => $this->deactivatedAt ? $this->deactivatedAt->getTimestamp() : null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $id = $data['suggestionBoxId'];
        $suggestionBox = new self(
            new SuggestionBoxId($id)
        );

        if (isset($data['deactivatedAt'])) {
            $date = new \DateTime();
            $date->setTimestamp($data['deactivatedAt']);
            $suggestionBox->setDeactivatedAt($date);
        }

        return $suggestionBox;
    }

    /**
     * @return \DateTime
     */
    public function getDeactivatedAt()
    {
        return $this->deactivatedAt;
    }

    /**
     * @param \DateTime $deactivatedAt
     */
    public function setDeactivatedAt($deactivatedAt)
    {
        $this->deactivatedAt = $deactivatedAt;
    }
}
