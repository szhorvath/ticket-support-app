<?php

namespace App\Models;

use App\Models\Model;

class Ticket extends Model
{
    protected $dates = [
        'updated_at',
        'created_at',
    ];
}
