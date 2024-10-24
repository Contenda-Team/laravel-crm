<?php

namespace Webkul\Case\Models;

use Illuminate\Database\Eloquent\Model;

class Case extends Model implements CaseContract
{
    protected $fillable = ['title', 'description', 'status', 'assigned_to'];
}