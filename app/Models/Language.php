<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
    ];

    protected $primaryKey = 'uuid';
}
