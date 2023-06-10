<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'fonts';

    protected $fillable = [
        'key',
        'name',
        'path',
    ];

    protected $primaryKey = 'uuid';
}
