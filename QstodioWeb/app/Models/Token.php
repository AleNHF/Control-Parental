<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'createDate',
        'status',
        'registerDate',
        'children_id',
    ];

    public function children()
    {
        return $this->belongsTo('App\Models\Children');
    }
}
