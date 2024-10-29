<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_id',
        'course_id',
        'div',
        'title',
        'dec',
        'file_path',
        'StartTime',
        'EndTime',
    ];
}
