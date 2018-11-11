<?php

namespace App\Models;

use App\Models\Model;

class User extends Model
{
    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function getFullNameOrUsername()
    {
        return $this->getFullName()?: $this->username;
    }

    public function getFullName()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }

        return null;
    }
}
