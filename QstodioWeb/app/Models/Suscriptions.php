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
        'planes_id',
        'user_id',
    ];

    public function plan(){
        return $this->BelongsTo('App\Models\Planes');
    }
    public function tutor(){
        return $this->BelongsTo('App\Models\Tutor');
    }
}
