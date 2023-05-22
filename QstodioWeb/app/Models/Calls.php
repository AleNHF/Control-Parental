<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;
    protected $fillable = [
        'received',
        'date',
        'duration',
        'contacts_id',
    ];

    public function contacts()
    {
        return $this->belongsTo('App\Models\contacts');
    }
}
