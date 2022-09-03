<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public $fillable = [
        'platform',
        'browser',
    ];
}
