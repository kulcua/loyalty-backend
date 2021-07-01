<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxDetails.
 */
class SuggestionBoxDetails implements SerializableReadModel, VersionableReadModel
{
    use Versionable;

    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * @var string
     */
    protected $senderId;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string
     */
    protected $problemType;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * SuggestionBoxDetails constructor.
     *
     * @param SuggestionBoxId $suggestionBoxId
     */
    public function __construct(SuggestionBoxId $suggestionBoxId)
    {
        $this->suggestionBoxId = $suggestionBoxId;
    }


    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    { 
        $suggestionBox = new self(new SuggestionBoxId($data['suggestionBoxId']));

        if (is_numeric($data['timestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($data['timestamp']);
            $data['timestamp'] = $tmp;
        }

        $suggestionBox->senderId = $data['senderId'];
        $suggestionBox->senderName = $data['senderName'];
        $suggestionBox->problemType = $data['problemType'];
        $suggestionBox->description = $data['description'];
        $suggestionBox->active = $data['active'];
        $suggestionBox->timestamp = $data['timestamp'];

        return $suggestionBox;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'suggestionBoxId' => (string) $this->suggestionBoxId,
            'senderId' => $this->senderId,
            'senderName' => $this->senderName,
            'problemType' => $this->problemType,
            'description' => $this->description,
            'active' => $this->active,
            'timestamp' => $this->getTimestamp() ? $this->getTimestamp()->getTimestamp() : null
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getSuggestionBoxId();
    }

    /**
     * @return SuggestionBoxId
     */
    public function getSuggestionBoxId(): SuggestionBoxId
    {
        return $this->suggestionBoxId;
    }

    /**
     * @return string $senderId
     */
    public function getSenderId(): string
    {
        return $this->senderId;
    }

    /**
     * @param string $senderId
     */
    public function setSenderId(?string $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return string $senderName
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string $problemType
     */
    public function getProblemType(): string
    {
        return $this->problemType;
    }

    /**
     * @param string $problemType
     */
    public function setProblemType(?string $problemType): void
    {
        $this->problemType = $problemType;
    }

    /**
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}