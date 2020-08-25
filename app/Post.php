<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    
    public function Customer () {
        return $this->belongsTo(App\Customer::class);
    }
}
