<?php

namespace App;

use App\Click;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['short_url', 'original_url', 'created_at', 'clicks_count'];   //TO-DO it should only include short_url.

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
