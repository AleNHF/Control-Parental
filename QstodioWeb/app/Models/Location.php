<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubication extends Model
{
    use HasFactory;
    protected $fillable = [
        'coordinates',
        'time',
        'date',
        'children_id',
    ];

    public function children()
    {
        return $this->belongsTo('App\Models\Children');
    }
}
