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
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function children(){
        return $this->hasMany(Children::class,'tutor_id','id');
    }
}
