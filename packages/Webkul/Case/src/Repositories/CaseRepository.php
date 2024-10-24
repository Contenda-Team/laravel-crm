<?php

namespace Webkul\Case\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Case\Contracts\CaseContract;

class CaseRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return CaseContract::class;
    }

    /**
     * Create a new case.
     *
     * @param array $data
     * @return \Webkul\Case\Contracts\CaseContract
     */
    public function create(array $data)
    {
        $case = parent::create($data);

        // Additional logic for creating a case

        return $case;
    }

    /**
     * Update an existing case.
     *
     * @param array $data
     * @param int $id
     * @return \Webkul\Case\Contracts\CaseContract
     */
    public function update(array $data, $id)
    {
        $case = $this->find($id);

        parent::update($data, $id);

        // Additional logic for updating a case

        return $case;
    }

    // Add more methods as needed for your application
}

