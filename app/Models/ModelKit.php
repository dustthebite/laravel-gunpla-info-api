<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKit extends Model
{
    use HasFactory;

    public function timeline(){
        return $this->belongsTo(Timeline::class);
    }
    public function grade(){
        return $this->belongsTo(Grade::class);
    }
    public function scale(){
        return $this->belongsTo(Scale::class);
    }

    protected $fillable = [
        'name',
        'height_centimeters',
        'isPBandai',
        'grade_id',
        'scale_id',
        'timeline_id',
        'release_date',
        'recommended_price_yen'
    ];
}
