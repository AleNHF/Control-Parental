<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'countDevices',
        'timePlan',
        'price',
        'status',
    ];

    public function tutor()
    {
        return $this->hasMany(Tutor::class, 'plan_id');
    }

    public function suscription(){
        return $this->hasMany('App\Models\Suscription');
    }
}
