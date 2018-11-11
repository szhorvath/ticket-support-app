<?php

namespace App\Models;

use App\Models\Model;

class Comment extends Model
{
    protected $dates = [
        'updated_at',
        'created_at',
    ];
}
