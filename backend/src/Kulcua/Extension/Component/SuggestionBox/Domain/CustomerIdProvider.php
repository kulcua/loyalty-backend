<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

interface CustomerIdProvider
{
    /**
     * @param array $customerData
     *
     * @return string
     */
    public function getId(array $customerData);
}
