<?php

namespace Webkul\Contact\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Contact\Contracts\PersonStatus as PersonStatusContract;

class PersonStatus extends Model implements PersonStatusContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort_order',
    ];

    /**
     * Get the name of the status.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the sort order.
     */
    public function getSortOrder(): int
    {
        return $this->sort_order;
    }

    /**
     * Get the persons associated with this status.
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Get the persons.
     */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class, 'status_id');
    }
}
