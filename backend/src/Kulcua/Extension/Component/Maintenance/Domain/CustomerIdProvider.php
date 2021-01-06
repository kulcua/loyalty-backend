<?php

namespace Kulcua\Extension\Component\Maintenance\Domain;

interface CustomerIdProvider
{
    /**
     * @param array $customerData
     *
     * @return string
     */
    public function getId(array $customerData);
}
