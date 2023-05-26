<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $fillable = [
        'name',
        'phoneNumber',
        'children_id',
    ];

    public function children()
    {
        return $this->belongsTo(Children::class, 'children_id');
    }
}
