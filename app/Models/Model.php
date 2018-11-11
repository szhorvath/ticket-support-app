<?php

namespace App\Models;

use Carbon\Carbon;

abstract class Model
{
    protected $dates = [];

    protected $hidden = [];

    public function __construct()
    {
        foreach ($this->dates as $date) {
            if (!$this->{$date}) {
                continue;
            }

            $this->{$date} = new Carbon($this->{$date});
        }

        foreach ($this->hidden as $hidden) {
            unset($this->{$hidden});
        }
    }

    public function __toString()
    {
        foreach ($this->hidden as $hidden) {
            unset($this->{$hidden});
        }

        return json_encode($this);
    }
}
