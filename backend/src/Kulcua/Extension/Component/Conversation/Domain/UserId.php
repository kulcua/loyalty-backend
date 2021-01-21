<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class LevelId.
 */
class LevelId implements Identifier
{
    private $levelId;

    public function __construct($levelId)
    {
        Assert::string($levelId);
        Assert::uuid($levelId);

        $this->levelId = $levelId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->levelId;
    }
}
