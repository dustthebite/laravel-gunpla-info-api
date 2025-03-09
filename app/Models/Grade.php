<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{

    public function modelKits()
    {
        return $this->hasMany(ModelKit::class);
    }
    
    protected $fillable = [
        'grade'
    ];
    
}
