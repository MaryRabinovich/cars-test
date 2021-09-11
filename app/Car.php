<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'marque',
        'model',
        'color_id',
        'number',
        'parking_paid',
        'comment'
    ];

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
