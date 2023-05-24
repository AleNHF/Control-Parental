<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'birthDay',
        'gender',
        'phoneNumber',
        'profilePhoto',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function children(){
        return $this->hasMany(Children::class,'tutor_id','id');
    }

    public function plans()
    {
        return $this->belongsToMany('App\Models\Plan', 'subscriptions');
    }

    public function suscription(){
        return $this->belongsToMany('App\Models\Suscription');
    }
}
