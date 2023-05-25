<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;

    protected $table = 'children';
    protected $fillable = [
        'name',
        'lastname',
        'alias',
        'birthDay',
        'gender',
        'tutor_id'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }
    
    public function locations()
    {
        return $this->hasMany(Location::class, 'children_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'children_id');
    }
}
