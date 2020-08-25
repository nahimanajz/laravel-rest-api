<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email'];
    
    public function posts (){
        return $this->hasMany(App\Post::class);
    }
    
}
