<?php

namespace Webkul\Contact\Contracts;

interface PersonStatus
{
    /**
     * Get the name of the status.
     */
    public function getName(): string;

    /**
     * Get the sort order.
     */
    public function getSortOrder(): int;

    /**
     * Get the persons associated with this status.
     */
    public function getPersons();
}