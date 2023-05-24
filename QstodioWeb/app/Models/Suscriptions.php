<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscriptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'startDate',
        'startEnd',
        'price',
        'plan_id',
        'tutor_id',
    ];

    public function plan(){
        return $this->belongsTo('App\Models\Plan');
    }

    public function tutor(){
        return $this->belongsTo('App\Models\Tutor');
    }
}
