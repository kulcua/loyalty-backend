<?php

namespace Kulcua\Extension\Component\Warranty\Domain;

interface CustomerIdProvider
{
    /**
     * @param array $customerData
     *
     * @return string
     */
    public function getId(array $customerData);
}
