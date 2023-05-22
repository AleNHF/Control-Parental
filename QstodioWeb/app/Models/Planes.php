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

    public function user()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    public function suscription(){
        return $this->belongsToMany('App\Models\Suscription');
    }
}
