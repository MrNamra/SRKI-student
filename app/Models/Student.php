<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'en_no',
        'name',
        'email',
        'ip',
        'stream_id',
        'year',
    ];
}
