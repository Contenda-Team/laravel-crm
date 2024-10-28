<?php

namespace Webkul\Contact\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Contact\Contracts\PersonStatus;

class PersonStatusRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return PersonStatus::class;
    }
}
