<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $fillable = [
        'createDate',
        'path',
        'children_id',
    ];

    public function children()
    {
        return $this->belongsTo(Children::class, 'children_id');
    }
}
