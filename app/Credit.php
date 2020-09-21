<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(App\User::class);
    }
}
